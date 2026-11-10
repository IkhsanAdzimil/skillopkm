<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // 1. BUAT TABEL BARU
        // ============================================================

        // Tabel sekolah (menggantikan kelas)
        if (!Schema::hasTable('sekolah')) {
            Schema::create('sekolah', function (Blueprint $table) {
                $table->id();
                $table->string('nama_sekolah');
                $table->text('deskripsi')->nullable();
                $table->enum('status', ['aktif', 'selesai'])->default('aktif');
                $table->timestamps();
            });
        }

        // Tabel sesi (kelompok belajar di bawah sekolah, misal Sesi 1, Sesi 2, dst.)
        if (!Schema::hasTable('sesi')) {
            Schema::create('sesi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
                $table->string('nama_sesi'); // Bebas diketik: "Sesi 1", "Sesi 2", dll.
                $table->string('tingkatan')->nullable(); // Contoh: "Course E", "Level 1", dll.
                $table->timestamps();
            });
        } elseif (!Schema::hasColumn('sesi', 'tingkatan')) {
            Schema::table('sesi', function (Blueprint $table) {
                $table->string('tingkatan')->nullable()->after('nama_sesi');
            });
        }

        // Pivot: sesi <-> guru (many-to-many, multi-guru per sesi)
        if (!Schema::hasTable('sesi_guru')) {
            Schema::create('sesi_guru', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sesi_id')->constrained('sesi')->cascadeOnDelete();
                $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['sesi_id', 'guru_id']);
            });
        }

        // Pivot: sesi <-> siswa (dengan kolom course/tingkatan per anak)
        if (!Schema::hasTable('sesi_siswa')) {
            Schema::create('sesi_siswa', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sesi_id')->constrained('sesi')->cascadeOnDelete();
                $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
                $table->string('course')->nullable();
                $table->timestamps();
                $table->unique(['sesi_id', 'siswa_id']);
            });
        }

        // Tabel pertemuan (menggantikan sesi_kelas yang berfungsi sebagai pertemuan harian)
        if (!Schema::hasTable('pertemuan')) {
            Schema::create('pertemuan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sesi_id')->constrained('sesi')->cascadeOnDelete();
                $table->date('tanggal');
                $table->string('materi')->nullable();
                $table->text('catatan_guru')->nullable();
                $table->timestamps();
                $table->unique(['sesi_id', 'tanggal']);
            });
        }

        // Tabel absensi guru per pertemuan (guru bisa saling mengabsenkan guru lain di sesi yang sama)
        if (!Schema::hasTable('absensi_guru')) {
            Schema::create('absensi_guru', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pertemuan_id')->constrained('pertemuan')->cascadeOnDelete();
                $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
                $table->enum('status', ['hadir', 'tidak_hadir'])->default('hadir');
                $table->timestamps();
                $table->unique(['pertemuan_id', 'guru_id']);
            });
        }

        // Tambah kolom nama_panggilan di tabel siswa
        if (!Schema::hasColumn('siswa', 'nama_panggilan')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->string('nama_panggilan')->nullable()->after('nama');
            });
        }

        // ============================================================
        // 2. LEPAS CONSTRAINT LAMA PADA ABSENSI_SISWA SEBELUM MIGRASI DATA
        // ============================================================
        if (Schema::hasColumn('absensi_siswa', 'sesi_kelas_id')) {
            try {
                Schema::table('absensi_siswa', function (Blueprint $table) {
                    $table->dropForeign(['sesi_kelas_id']);
                });
            } catch (\Exception $e) {
                // Abaikan jika foreign key sudah tidak ada
            }

            try {
                Schema::table('absensi_siswa', function (Blueprint $table) {
                    $table->dropUnique('sesi_siswa_unique');
                });
            } catch (\Exception $e) {
                // Abaikan jika unique index sudah tidak ada
            }
        }

        // ============================================================
        // 3. MIGRASI DATA LAMA KE STRUKTUR BARU (TANPA HAPUS AKUN ADMIN/GURU)
        // ============================================================
        $kelasToSesi = [];

        if (Schema::hasTable('kelas')) {
            $kelasList = DB::table('kelas')->get();

            foreach ($kelasList as $kelas) {
                // 1) kelas → sekolah
                DB::table('sekolah')->insert([
                    'id'           => $kelas->id,
                    'nama_sekolah' => $kelas->nama_kelas,
                    'deskripsi'    => $kelas->deskripsi,
                    'status'       => $kelas->status ?? 'aktif',
                    'created_at'   => $kelas->created_at ?? now(),
                    'updated_at'   => $kelas->updated_at ?? now(),
                ]);

                // 2) Buat Sesi 1 (menampung data lama) dan Sesi 2 (siap pakai)
                $sesi1Id = DB::table('sesi')->insertGetId([
                    'sekolah_id' => $kelas->id,
                    'nama_sesi'  => 'Sesi 1',
                    'tingkatan'  => null,
                    'created_at' => $kelas->created_at ?? now(),
                    'updated_at' => $kelas->updated_at ?? now(),
                ]);

                DB::table('sesi')->insert([
                    'sekolah_id' => $kelas->id,
                    'nama_sesi'  => 'Sesi 2',
                    'tingkatan'  => null,
                    'created_at' => $kelas->created_at ?? now(),
                    'updated_at' => $kelas->updated_at ?? now(),
                ]);

                $kelasToSesi[$kelas->id] = $sesi1Id;

                // 3) Migrasi guru pengampu → sesi_guru
                if (!empty($kelas->guru_id)) {
                    DB::table('sesi_guru')->insert([
                        'sesi_id'    => $sesi1Id,
                        'guru_id'    => $kelas->guru_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // 4) Migrasi kelas_siswa → sesi_siswa
            if (Schema::hasTable('kelas_siswa')) {
                $kelasSiswaList = DB::table('kelas_siswa')->get();
                foreach ($kelasSiswaList as $ks) {
                    $sesiId = $kelasToSesi[$ks->kelas_id] ?? null;
                    if ($sesiId) {
                        $exists = DB::table('sesi_siswa')
                            ->where('sesi_id', $sesiId)
                            ->where('siswa_id', $ks->siswa_id)
                            ->exists();
                        if (!$exists) {
                            DB::table('sesi_siswa')->insert([
                                'sesi_id'    => $sesiId,
                                'siswa_id'   => $ks->siswa_id,
                                'course'     => null,
                                'created_at' => $ks->created_at ?? now(),
                                'updated_at' => $ks->updated_at ?? now(),
                            ]);
                        }
                    }
                }
            }

            // 5) Migrasi sesi_kelas → pertemuan (pertahankan ID agar absensi_siswa tetap sinkron)
            if (Schema::hasTable('sesi_kelas')) {
                $sesiKelasList = DB::table('sesi_kelas')->get();
                foreach ($sesiKelasList as $sk) {
                    $sesiId = $kelasToSesi[$sk->kelas_id] ?? null;
                    if ($sesiId) {
                        DB::table('pertemuan')->insert([
                            'id'           => $sk->id,
                            'sesi_id'      => $sesiId,
                            'tanggal'      => $sk->tanggal,
                            'materi'       => $sk->materi,
                            'catatan_guru' => $sk->catatan_guru,
                            'created_at'   => $sk->created_at ?? now(),
                            'updated_at'   => $sk->updated_at ?? now(),
                        ]);

                        // Catat kehadiran guru pada pertemuan lama jika ada guru_id
                        if (!empty($sk->guru_id)) {
                            DB::table('absensi_guru')->insertOrIgnore([
                                'pertemuan_id' => $sk->id,
                                'guru_id'      => $sk->guru_id,
                                'status'       => 'hadir',
                                'created_at'   => $sk->created_at ?? now(),
                                'updated_at'   => $sk->updated_at ?? now(),
                            ]);
                        }
                    }
                }
            }
        }

        // ============================================================
        // 4. MODIFIKASI TABEL ABSENSI_SISWA (pertemuan_id, notes_1, notes_2)
        // ============================================================
        if (Schema::hasColumn('absensi_siswa', 'sesi_kelas_id')) {
            Schema::table('absensi_siswa', function (Blueprint $table) {
                $table->renameColumn('sesi_kelas_id', 'pertemuan_id');
            });
        }

        // Bersihkan baris yatim (jika ada) sebelum pasang foreign key baru
        $validPertemuanIds = DB::table('pertemuan')->pluck('id')->toArray();
        if (!empty($validPertemuanIds)) {
            DB::table('absensi_siswa')->whereNotIn('pertemuan_id', $validPertemuanIds)->delete();
        } else {
            DB::table('absensi_siswa')->delete();
        }

        Schema::table('absensi_siswa', function (Blueprint $table) {
            $table->foreign('pertemuan_id')->references('id')->on('pertemuan')->cascadeOnDelete();
            $table->unique(['pertemuan_id', 'siswa_id'], 'pertemuan_siswa_unique');
        });

        if (Schema::hasColumn('absensi_siswa', 'notes')) {
            Schema::table('absensi_siswa', function (Blueprint $table) {
                $table->renameColumn('notes', 'notes_1');
            });
        }

        if (!Schema::hasColumn('absensi_siswa', 'notes_2')) {
            Schema::table('absensi_siswa', function (Blueprint $table) {
                $table->text('notes_2')->nullable()->after('notes_1');
            });
        }

        // ============================================================
        // 5. HAPUS TABEL LAMA
        // ============================================================
        Schema::dropIfExists('kelas_siswa');
        Schema::dropIfExists('sesi_kelas');
        Schema::dropIfExists('kelas');
    }

    public function down(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas');
            $table->foreignId('guru_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('kelas_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['kelas_id', 'siswa_id']);
        });

        Schema::create('sesi_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('guru_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal');
            $table->string('materi')->nullable();
            $table->text('catatan_guru')->nullable();
            $table->timestamps();
            $table->unique(['kelas_id', 'tanggal'], 'sesi_kelas_tanggal_unique');
        });

        if (Schema::hasColumn('absensi_siswa', 'notes_2')) {
            Schema::table('absensi_siswa', function (Blueprint $table) {
                $table->dropColumn('notes_2');
            });
        }

        if (Schema::hasColumn('absensi_siswa', 'notes_1')) {
            Schema::table('absensi_siswa', function (Blueprint $table) {
                $table->renameColumn('notes_1', 'notes');
            });
        }

        Schema::table('absensi_siswa', function (Blueprint $table) {
            $table->dropForeign(['pertemuan_id']);
            $table->dropUnique('pertemuan_siswa_unique');
            $table->renameColumn('pertemuan_id', 'sesi_kelas_id');
        });

        Schema::table('absensi_siswa', function (Blueprint $table) {
            $table->foreign('sesi_kelas_id')->references('id')->on('sesi_kelas')->cascadeOnDelete();
            $table->unique(['sesi_kelas_id', 'siswa_id'], 'sesi_siswa_unique');
        });

        if (Schema::hasColumn('siswa', 'nama_panggilan')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropColumn('nama_panggilan');
            });
        }

        Schema::dropIfExists('absensi_guru');
        Schema::dropIfExists('pertemuan');
        Schema::dropIfExists('sesi_siswa');
        Schema::dropIfExists('sesi_guru');
        Schema::dropIfExists('sesi');
        Schema::dropIfExists('sekolah');
    }
};
