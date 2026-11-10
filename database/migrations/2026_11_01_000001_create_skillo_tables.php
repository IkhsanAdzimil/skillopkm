<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Master: Kelas
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas');
            $table->foreignId('guru_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->timestamps();
        });

        // 2. Tabel Master: Siswa
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->string('nama');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        // 3. Tabel Pivot: Kelas <-> Siswa (Many-to-Many)
        Schema::create('kelas_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['kelas_id', 'siswa_id']);
        });

        // 4. Tabel Master: Sesi Jurnal Kelas / Pertemuan (Header)
        // Memisahkan entitas sesi mengajar guru dari data baris kehadiran siswa
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

        // 5. Tabel Detail: Absensi Siswa & Catatan Progres (Item / Detail)
        // Menyimpan status presensi dan perkembangan individual siswa pada sesi terkait
        Schema::create('absensi_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_kelas_id')->constrained('sesi_kelas')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alfa'])->default('hadir');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['sesi_kelas_id', 'siswa_id'], 'sesi_siswa_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_siswa');
        Schema::dropIfExists('sesi_kelas');
        Schema::dropIfExists('kelas_siswa');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('kelas');
    }
};
