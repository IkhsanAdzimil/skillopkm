<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\AbsensiSiswa;
use App\Models\Pertemuan;
use App\Models\Sekolah;
use App\Models\Sesi;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $sekolahList = Sekolah::with([
            'sesi' => fn($q) => $q->with([
                'guru'  => fn($g) => $g->orderBy('name'),
                'siswa' => fn($s) => $s->orderBy('nama'),
            ])->withCount('siswa')->orderBy('id'),
        ])->latest()->get();

        $sesiList = Sesi::with(['sekolah', 'guru', 'siswa'])
            ->withCount('siswa')
            ->orderBy('sekolah_id')
            ->orderBy('id')
            ->get();

        $totalSekolah  = $sekolahList->count();
        $totalSesi     = $sesiList->count();
        $totalGuru     = User::where('role', 'guru')->count();
        $totalSiswa    = Siswa::count();
        $guruList      = User::where('role', 'guru')->with('sesi.sekolah')->orderBy('name')->get();
        $arsipGuruList = User::onlyTrashed()->where('role', 'guru')->orderBy('name')->get();
        $allSiswa      = Siswa::with('sesi.sekolah')->orderBy('nama')->get();

        // --- Filter Rekapan Mingguan Langsung di Dashboard Admin (Tanpa Harus Download CSV/Excel) ---
        try {
            $rekapMulai = $request->filled('rekap_mulai')
                ? Carbon::parse($request->query('rekap_mulai'))->format('Y-m-d')
                : now()->startOfWeek()->format('Y-m-d');
        } catch (\Exception $e) {
            $rekapMulai = now()->startOfWeek()->format('Y-m-d');
        }

        try {
            $rekapAkhir = $request->filled('rekap_akhir')
                ? Carbon::parse($request->query('rekap_akhir'))->format('Y-m-d')
                : now()->endOfWeek()->format('Y-m-d');
        } catch (\Exception $e) {
            $rekapAkhir = now()->endOfWeek()->format('Y-m-d');
        }

        $rekapSekolahId = $request->query('rekap_sekolah_id');
        $rekapSesiId    = $request->query('rekap_sesi_id');

        $rekapPertemuanQuery = Pertemuan::with([
            'sesi.sekolah',
            'sesi.guru',
            'sesi.siswa',
            'absensiGuru.guru',
            'absensiSiswa.siswa',
        ])->whereBetween('tanggal', [$rekapMulai, $rekapAkhir]);

        if (!empty($rekapSekolahId)) {
            $rekapPertemuanQuery->whereHas('sesi', function ($q) use ($rekapSekolahId) {
                $q->where('sekolah_id', $rekapSekolahId);
            });
        }

        if (!empty($rekapSesiId)) {
            $rekapPertemuanQuery->where('sesi_id', $rekapSesiId);
        }

        $rekapPertemuanList = $rekapPertemuanQuery
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // Susun baris detail rekap absensi siswa beserta course & kehadiran guru di sesi tersebut
        $rekapAbsensiRows = collect();
        $rekapStats = [
            'total_pertemuan' => $rekapPertemuanList->count(),
            'hadir'           => 0,
            'sakit'           => 0,
            'izin'            => 0,
            'alfa'            => 0,
            'bercatatan'      => 0,
        ];

        foreach ($rekapPertemuanList as $pertemuan) {
            $sesi    = $pertemuan->sesi;
            $sekolah = $sesi?->sekolah;

            // Peta course per siswa di sesi ini
            $courseMap = $sesi
                ? $sesi->siswa->pluck('pivot.course', 'id')
                : collect();

            // Peta kehadiran guru pada pertemuan ini
            $guruStatusMap = $pertemuan->absensiGuru->keyBy('guru_id');

            foreach ($pertemuan->absensiSiswa as $absen) {
                $st = $absen->status ?? 'hadir';
                if (isset($rekapStats[$st])) {
                    $rekapStats[$st]++;
                }
                if (!empty($absen->notes_1) || !empty($absen->notes_2)) {
                    $rekapStats['bercatatan']++;
                }

                $rekapAbsensiRows->push((object) [
                    'pertemuan_id'   => $pertemuan->id,
                    'tanggal'        => $pertemuan->tanggal,
                    'materi'         => $pertemuan->materi,
                    'sekolah_nama'   => $sekolah?->nama_sekolah ?? '-',
                    'sesi_nama'      => $sesi?->nama_sesi ?? '-',
                    'sesi_tingkatan' => $sesi?->tingkatan,
                    'siswa'          => $absen->siswa,
                    'course'         => $courseMap[$absen->siswa_id] ?? $sesi?->tingkatan ?? '-',
                    'status'         => $st,
                    'notes_1'        => $absen->notes_1,
                    'notes_2'        => $absen->notes_2,
                    'guru_sesi'      => $sesi?->guru ?? collect(),
                    'guru_absensi'   => $guruStatusMap,
                ]);
            }
        }

        return view('admin.dashboard', compact(
            'sekolahList',
            'sesiList',
            'totalSekolah',
            'totalSesi',
            'totalGuru',
            'totalSiswa',
            'guruList',
            'arsipGuruList',
            'allSiswa',
            'rekapMulai',
            'rekapAkhir',
            'rekapSekolahId',
            'rekapSesiId',
            'rekapPertemuanList',
            'rekapAbsensiRows',
            'rekapStats'
        ));
    }

    // ============================================================
    // MANAJEMEN SEKOLAH & SESI
    // ============================================================

    public function storeSekolah(Request $request)
    {
        $data = $request->validate([
            'nama_sekolah'       => 'required|string|max:255',
            'deskripsi'          => 'nullable|string|max:1000',
            'sesi'               => 'nullable|array',
            'sesi.*.nama_sesi'   => 'nullable|string|max:255',
            'sesi.*.tingkatan'   => 'nullable|string|max:255',
            'sesi.*.guru_ids'    => 'nullable|array',
            'sesi.*.guru_ids.*'  => 'exists:users,id',
        ]);

        DB::transaction(function () use ($data) {
            $sekolah = Sekolah::create([
                'nama_sekolah' => trim($data['nama_sekolah']),
                'deskripsi'    => $data['deskripsi'] ?? null,
                'status'       => 'aktif',
            ]);

            $sesiInputs = collect($data['sesi'] ?? [])
                ->filter(fn($item) => !empty(trim($item['nama_sesi'] ?? '')))
                ->values();

            // Jika admin tidak mengisi baris sesi, buat default Sesi 1 dan Sesi 2
            if ($sesiInputs->isEmpty()) {
                $sesiInputs = collect([
                    ['nama_sesi' => 'Sesi 1', 'tingkatan' => null, 'guru_ids' => []],
                    ['nama_sesi' => 'Sesi 2', 'tingkatan' => null, 'guru_ids' => []],
                ]);
            }

            foreach ($sesiInputs as $sInput) {
                $sesi = Sesi::create([
                    'sekolah_id' => $sekolah->id,
                    'nama_sesi'  => trim($sInput['nama_sesi']),
                    'tingkatan'  => !empty($sInput['tingkatan']) ? trim($sInput['tingkatan']) : null,
                ]);

                if (!empty($sInput['guru_ids']) && is_array($sInput['guru_ids'])) {
                    $sesi->guru()->sync($sInput['guru_ids']);
                }
            }
        });

        return back()->with('success', 'Sekolah "' . $data['nama_sekolah'] . '" beserta pembagian sesinya berhasil dibuat!');
    }

    public function updateSekolah(Request $request, Sekolah $sekolah)
    {
        $data = $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'deskripsi'    => 'nullable|string|max:1000',
        ]);

        $sekolah->update([
            'nama_sekolah' => trim($data['nama_sekolah']),
            'deskripsi'    => $data['deskripsi'] ?? null,
        ]);

        return back()->with('success', 'Data sekolah "' . $sekolah->nama_sekolah . '" berhasil diperbarui!');
    }

    public function destroySekolah(Sekolah $sekolah)
    {
        $namaSekolah = $sekolah->nama_sekolah;

        DB::transaction(function () use ($sekolah) {
            $sesiList = $sekolah->sesi()->with('siswa')->get();
            $affectedSiswaIds = collect();

            foreach ($sesiList as $sesi) {
                $affectedSiswaIds = $affectedSiswaIds->merge($sesi->siswa->pluck('id'));

                $pertemuanIds = $sesi->pertemuan()->pluck('id');
                if ($pertemuanIds->isNotEmpty()) {
                    AbsensiSiswa::whereIn('pertemuan_id', $pertemuanIds)->delete();
                    AbsensiGuru::whereIn('pertemuan_id', $pertemuanIds)->delete();
                }

                $sesi->pertemuan()->delete();
                $sesi->siswa()->detach();
                $sesi->guru()->detach();
                $sesi->delete();
            }

            // Hapus permanen murid yang tidak lagi terdaftar di sesi manapun
            foreach ($affectedSiswaIds->unique() as $siswaId) {
                $siswa = Siswa::find($siswaId);
                if ($siswa && $siswa->sesi()->count() === 0) {
                    $siswa->delete();
                }
            }

            $sekolah->delete();
        });

        return back()->with('success', 'Sekolah "' . $namaSekolah . '" beserta seluruh sesi, jurnal pertemuan, dan riwayat absensinya berhasil dihapus.');
    }

    public function storeSesi(Request $request)
    {
        $data = $request->validate([
            'sekolah_id' => 'required|exists:sekolah,id',
            'nama_sesi'  => 'required|string|max:255',
            'tingkatan'  => 'nullable|string|max:255',
            'guru_ids'   => 'nullable|array',
            'guru_ids.*' => 'exists:users,id',
        ]);

        $sekolah = Sekolah::findOrFail($data['sekolah_id']);

        $sesi = Sesi::create([
            'sekolah_id' => $sekolah->id,
            'nama_sesi'  => trim($data['nama_sesi']),
            'tingkatan'  => !empty($data['tingkatan']) ? trim($data['tingkatan']) : null,
        ]);

        if (!empty($data['guru_ids'])) {
            $sesi->guru()->sync($data['guru_ids']);
        }

        return back()->with('success', 'Sesi "' . $sesi->nama_sesi . '" berhasil ditambahkan ke ' . $sekolah->nama_sekolah . '!');
    }

    public function updateSesi(Request $request, Sesi $sesi)
    {
        $data = $request->validate([
            'nama_sesi'  => 'required|string|max:255',
            'tingkatan'  => 'nullable|string|max:255',
            'guru_ids'   => 'nullable|array',
            'guru_ids.*' => 'exists:users,id',
        ]);

        $sesi->update([
            'nama_sesi' => trim($data['nama_sesi']),
            'tingkatan' => !empty($data['tingkatan']) ? trim($data['tingkatan']) : null,
        ]);

        $sesi->guru()->sync($data['guru_ids'] ?? []);

        return back()->with('success', 'Sesi "' . $sesi->nama_sesi . '" (' . ($sesi->sekolah?->nama_sekolah ?? '') . ') berhasil diperbarui!');
    }

    public function destroySesi(Sesi $sesi)
    {
        $namaSesi    = $sesi->nama_sesi;
        $namaSekolah = $sesi->sekolah?->nama_sekolah ?? '';

        DB::transaction(function () use ($sesi) {
            $pertemuanIds = $sesi->pertemuan()->pluck('id');
            if ($pertemuanIds->isNotEmpty()) {
                AbsensiSiswa::whereIn('pertemuan_id', $pertemuanIds)->delete();
                AbsensiGuru::whereIn('pertemuan_id', $pertemuanIds)->delete();
            }
            $sesi->pertemuan()->delete();
            $sesi->siswa()->detach();
            $sesi->guru()->detach();
            $sesi->delete();
        });

        return back()->with('success', 'Sesi "' . $namaSesi . '" pada ' . $namaSekolah . ' berhasil dihapus.');
    }

    // ============================================================
    // MANAJEMEN MURID & IMPORT EXCEL PER SESI
    // ============================================================

    private function generateUniqueNim(): string
    {
        $lastId = Siswa::max('id') ?? 0;
        $generatedNim = 'SKL' . date('Y') . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
        while (Siswa::where('nim', $generatedNim)->exists()) {
            $lastId++;
            $generatedNim = 'SKL' . date('Y') . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
        }
        return $generatedNim;
    }

    public function storeSiswa(Request $request)
    {
        $request->validate([
            'sesi_id'    => 'required|exists:sesi,id',
            'tipe_input' => 'nullable|in:baru,pilih',
            'course'     => 'nullable|string|max:255',
        ]);

        $sesi      = Sesi::with('sekolah')->findOrFail($request->sesi_id);
        $tipeInput = $request->input('tipe_input', 'baru');
        $course    = !empty($request->course) ? trim($request->course) : ($sesi->tingkatan ?? null);
        $labelSesi = ($sesi->sekolah?->nama_sekolah ?? 'Sekolah') . ' — ' . $sesi->nama_sesi;

        // 1. Input Murid Baru (Nama Lengkap, Nama Panggilan, Nomor Telepon)
        if ($tipeInput === 'baru') {
            $data = $request->validate([
                'nama'           => 'required|string|max:255',
                'nama_panggilan' => 'nullable|string|max:100',
                'no_hp'          => 'nullable|string|max:30',
            ]);

            $siswa = Siswa::create([
                'nim'            => $this->generateUniqueNim(),
                'nama'           => trim($data['nama']),
                'nama_panggilan' => !empty($data['nama_panggilan']) ? trim($data['nama_panggilan']) : null,
                'no_hp'          => !empty($data['no_hp']) ? trim($data['no_hp']) : null,
            ]);

            $sesi->siswa()->attach($siswa->id, ['course' => $course]);

            return back()->with('success', 'Murid baru "' . $siswa->nama . '" berhasil ditambahkan ke ' . $labelSesi . '!');
        }

        // 2. Pilih Murid yang Sudah Terdaftar
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
        ]);

        $siswa = Siswa::findOrFail($request->siswa_id);

        if ($sesi->siswa()->where('siswa_id', $siswa->id)->exists()) {
            return back()->with('error', 'Murid "' . $siswa->nama . '" sudah terdaftar di ' . $labelSesi . '.');
        }

        $sesi->siswa()->attach($siswa->id, ['course' => $course]);

        return back()->with('success', 'Murid "' . $siswa->nama . '" berhasil didaftarkan ke ' . $labelSesi . '!');
    }

    /**
     * Import daftar murid dari file Excel (.xlsx / .xls / .csv) ke Sesi yang dipilih Admin.
     * Struktur kolom tabel atas di Excel:
     * Nomor | Nama Lengkap | Nama Panggilan | Nomor Telepon | (Opsional: Course / Tingkatan)
     */
    public function importSiswa(Request $request)
    {
        $request->validate([
            'sesi_id'        => 'required|exists:sesi,id',
            'default_course' => 'nullable|string|max:255',
            'parsed_rows'    => 'nullable|string',
            'file_excel'     => 'nullable|file|max:10240',
        ]);

        $sesi          = Sesi::with('sekolah')->findOrFail($request->sesi_id);
        $defaultCourse = !empty($request->default_course)
            ? trim($request->default_course)
            : ($sesi->tingkatan ?? null);
        $labelSesi     = ($sesi->sekolah?->nama_sekolah ?? 'Sekolah') . ' — ' . $sesi->nama_sesi;

        $records = [];

        // Prioritas 1: Gunakan hasil parse dari SheetJS frontend jika dikirim dalam format JSON
        if ($request->filled('parsed_rows')) {
            $decoded = json_decode($request->input('parsed_rows'), true);
            if (is_array($decoded) && !empty($decoded)) {
                $records = $decoded;
            }
        }

        // Prioritas 2: Parse file Excel/CSV secara langsung di backend PHP
        if (empty($records) && $request->hasFile('file_excel')) {
            $records = $this->parseSpreadsheetFile($request->file('file_excel'));
        }

        if (empty($records)) {
            return back()->with('error', 'File Excel kosong atau format tidak terbaca. Pastikan tabel memiliki kolom: Nomor, Nama Lengkap, Nama Panggilan, dan Nomor Telepon.');
        }

        $addedCount   = 0;
        $updatedCount = 0;

        DB::transaction(function () use ($records, $sesi, $defaultCourse, &$addedCount, &$updatedCount) {
            foreach ($records as $row) {
                $namaLengkap   = trim((string) ($row['nama_lengkap'] ?? $row['nama'] ?? ''));
                $namaPanggilan = trim((string) ($row['nama_panggilan'] ?? ''));
                $noHp          = trim((string) ($row['no_hp'] ?? $row['nomor_telepon'] ?? ''));
                $courseRow     = trim((string) ($row['course'] ?? ''));

                if ($namaLengkap === '') {
                    continue;
                }

                // Abaikan jika baris ternyata masih merupakan baris judul/header
                $lowerNama = mb_strtolower($namaLengkap);
                if (in_array($lowerNama, ['nama lengkap', 'nama', 'nama siswa', 'nama murid', 'full name'], true) || str_contains($lowerNama, '[contoh]') || str_contains($lowerNama, '[example]')) {
                    continue;
                }

                $finalCourse = $courseRow !== '' ? $courseRow : $defaultCourse;

                // Cari apakah murid sudah ada berdasarkan nama lengkap (dan no_hp bila tersedia)
                $query = Siswa::whereRaw('LOWER(nama) = ?', [$lowerNama]);
                if ($noHp !== '') {
                    $existing = (clone $query)->where('no_hp', $noHp)->first() ?? $query->first();
                } else {
                    $existing = $query->first();
                }

                if ($existing) {
                    $existing->update([
                        'nama_panggilan' => $namaPanggilan !== '' ? $namaPanggilan : $existing->nama_panggilan,
                        'no_hp'          => $noHp !== '' ? $noHp : $existing->no_hp,
                    ]);
                    $siswa = $existing;
                } else {
                    $siswa = Siswa::create([
                        'nim'            => $this->generateUniqueNim(),
                        'nama'           => $namaLengkap,
                        'nama_panggilan' => $namaPanggilan !== '' ? $namaPanggilan : null,
                        'no_hp'          => $noHp !== '' ? $noHp : null,
                    ]);
                }

                $alreadyInSesi = $sesi->siswa()->where('siswa_id', $siswa->id)->exists();
                if ($alreadyInSesi) {
                    if ($finalCourse !== null) {
                        $sesi->siswa()->updateExistingPivot($siswa->id, ['course' => $finalCourse]);
                    }
                    $updatedCount++;
                } else {
                    $sesi->siswa()->attach($siswa->id, ['course' => $finalCourse]);
                    $addedCount++;
                }
            }
        });

        if ($addedCount === 0 && $updatedCount === 0) {
            return back()->with('error', 'Tidak ada baris data murid valid yang ditemukan di dalam file.');
        }

        $msg = "Import Excel ke {$labelSesi} berhasil! {$addedCount} murid ditambahkan";
        if ($updatedCount > 0) {
            $msg .= " dan {$updatedCount} murid yang sudah ada diperbarui";
        }
        $msg .= ".";

        return back()->with('success', $msg);
    }

    /**
     * Download Template Excel (.xlsx) untuk Import Murid.
     */
    public function downloadTemplateSiswa()
    {
        $templatePath = public_path('templates/Template_Import_Murid_Skillo.xlsx');
        if (file_exists($templatePath)) {
            return response()->download($templatePath, 'Template_Import_Murid_Skillo.xlsx');
        }

        // Fallback: generate CSV/XLS kompatibel jika file statis tidak ditemukan
        $csv = "Nomor,Nama Lengkap,Nama Panggilan,Nomor Telepon,Course\n"
             . "1,Keano Putra Pratama,Keano,081234567801,Course E\n"
             . "2,Aisyah Putri Maharani,Aisyah,081234567802,Course E\n"
             . "3,Muhammad Danendra,Danen,081234567803,Course E\n";

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="Template_Import_Murid_Skillo.csv"');
    }

    public function updateSesiSiswa(Request $request, Sesi $sesi, Siswa $siswa)
    {
        $data = $request->validate([
            'course'         => 'nullable|string|max:255',
            'nama_panggilan' => 'nullable|string|max:100',
            'no_hp'          => 'nullable|string|max:30',
        ]);

        if ($request->has('nama_panggilan') || $request->has('no_hp')) {
            $siswa->update([
                'nama_panggilan' => !empty($data['nama_panggilan']) ? trim($data['nama_panggilan']) : $siswa->nama_panggilan,
                'no_hp'          => !empty($data['no_hp']) ? trim($data['no_hp']) : $siswa->no_hp,
            ]);
        }

        $sesi->siswa()->updateExistingPivot($siswa->id, [
            'course' => !empty($data['course']) ? trim($data['course']) : null,
        ]);

        return back()->with('success', 'Data murid "' . $siswa->nama . '" pada ' . $sesi->nama_sesi . ' berhasil diperbarui.');
    }

    public function destroySesiSiswa(Sesi $sesi, Siswa $siswa)
    {
        $sesi->siswa()->detach($siswa->id);
        $labelSesi = ($sesi->sekolah?->nama_sekolah ?? '') . ' — ' . $sesi->nama_sesi;
        return back()->with('success', 'Murid "' . $siswa->nama . '" berhasil dikeluarkan dari ' . $labelSesi . '.');
    }

    public function updateSiswa(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'nama'           => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:100',
            'no_hp'          => 'nullable|string|max:30',
        ]);

        $siswa->update([
            'nama'           => trim($data['nama']),
            'nama_panggilan' => !empty($data['nama_panggilan']) ? trim($data['nama_panggilan']) : null,
            'no_hp'          => !empty($data['no_hp']) ? trim($data['no_hp']) : null,
        ]);

        return back()->with('success', 'Data profil murid "' . $siswa->nama . '" berhasil diperbarui!');
    }

    public function destroySiswa(Siswa $siswa)
    {
        $nama = $siswa->nama;
        $siswa->delete();
        return back()->with('success', 'Data murid "' . $nama . '" berhasil dihapus secara permanen dari sistem.');
    }

    // ============================================================
    // MANAJEMEN GURU
    // ============================================================

    public function storeGuru(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'no_hp'    => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'     => $data['name'],
            'username' => $data['username'],
            'email'    => $data['email'],
            'no_hp'    => $data['no_hp'] ?? null,
            'password' => Hash::make($data['password']),
            'role'     => 'guru',
        ]);

        return back()->with('success', 'Akun Guru "' . $data['name'] . '" berhasil dibuat!');
    }

    public function destroyGuru(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->role !== 'guru') {
            return back()->with('error', 'Akun bukan guru.');
        }

        $nama = $user->name;
        $tipe = $request->input('tipe_hapus', 'daftar');

        if ($tipe === 'permanen') {
            DB::table('sesi_guru')->where('guru_id', $user->id)->delete();
            $user->forceDelete();
            return back()->with('success', 'Akun Guru "' . $nama . '" berhasil dihapus secara permanen dari sistem.');
        } else {
            DB::table('sesi_guru')->where('guru_id', $user->id)->delete();
            $user->delete();
            return back()->with('success', 'Akun Guru "' . $nama . '" berhasil dihapus dari daftar aktif.');
        }
    }

    public function restoreGuru($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        if ($user->role !== 'guru') {
            return back()->with('error', 'Akun bukan guru.');
        }

        $user->restore();
        return back()->with('success', 'Akun Guru "' . $user->name . '" berhasil dipulihkan ke daftar aktif.');
    }

    // ============================================================
    // HELPER PARSER EXCEL (.xlsx / .csv) NATIVE PHP
    // ============================================================

    private function parseSpreadsheetFile(UploadedFile $file): array
    {
        $ext  = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        $rawRows = [];
        if ($ext === 'xlsx' && class_exists(\ZipArchive::class)) {
            $rawRows = $this->parseXlsxRows($path);
        } elseif (in_array($ext, ['csv', 'txt'], true)) {
            $rawRows = $this->parseCsvRows($path);
        } else {
            // Coba XLSX dulu, jika gagal fallback ke CSV
            if (class_exists(\ZipArchive::class)) {
                $rawRows = $this->parseXlsxRows($path);
            }
            if (empty($rawRows)) {
                $rawRows = $this->parseCsvRows($path);
            }
        }

        if (empty($rawRows)) {
            return [];
        }

        // Cari baris header (Nomor, Nama Lengkap, Nama Panggilan, Nomor Telepon)
        $headerIdx = null;
        $colMap = [
            'nama_lengkap'   => 1,
            'nama_panggilan' => 2,
            'no_hp'          => 3,
            'course'         => 4,
        ];

        foreach ($rawRows as $idx => $r) {
            $normalized = array_map(fn($v) => mb_strtolower(trim((string) $v)), $r);
            $joined     = implode(' | ', $normalized);

            if (str_contains($joined, 'nama') || str_contains($joined, 'panggilan') || str_contains($joined, 'telepon')) {
                $headerIdx = $idx;
                $detectedMap = [];
                foreach ($normalized as $colIndex => $colName) {
                    if ($colName === '') continue;
                    if (str_contains($colName, 'panggilan') || str_contains($colName, 'nickname')) {
                        $detectedMap['nama_panggilan'] = $colIndex;
                    } elseif (str_contains($colName, 'lengkap') || $colName === 'nama' || str_contains($colName, 'nama siswa') || str_contains($colName, 'nama murid')) {
                        $detectedMap['nama_lengkap'] = $colIndex;
                    } elseif (str_contains($colName, 'telepon') || str_contains($colName, 'telp') || str_contains($colName, 'hp') || str_contains($colName, 'wa') || str_contains($colName, 'whatsapp')) {
                        $detectedMap['no_hp'] = $colIndex;
                    } elseif (str_contains($colName, 'course') || str_contains($colName, 'tingkat') || str_contains($colName, 'level')) {
                        $detectedMap['course'] = $colIndex;
                    }
                }
                if (isset($detectedMap['nama_lengkap'])) {
                    $colMap['nama_lengkap']   = $detectedMap['nama_lengkap'];
                    $colMap['nama_panggilan'] = $detectedMap['nama_panggilan'] ?? null;
                    $colMap['no_hp']          = $detectedMap['no_hp'] ?? null;
                    $colMap['course']         = $detectedMap['course'] ?? null;
                }
                break;
            }
        }

        $startIdx = $headerIdx !== null ? $headerIdx + 1 : 0;
        $result   = [];

        for ($i = $startIdx; $i < count($rawRows); $i++) {
            $row = $rawRows[$i];
            $namaLengkap = isset($colMap['nama_lengkap'], $row[$colMap['nama_lengkap']])
                ? trim((string) $row[$colMap['nama_lengkap']])
                : '';

            if ($namaLengkap === '') {
                continue;
            }

            $namaPanggilan = ($colMap['nama_panggilan'] !== null && isset($row[$colMap['nama_panggilan']]))
                ? trim((string) $row[$colMap['nama_panggilan']])
                : '';

            $noHp = ($colMap['no_hp'] !== null && isset($row[$colMap['no_hp']]))
                ? trim((string) $row[$colMap['no_hp']])
                : '';

            $course = ($colMap['course'] !== null && isset($row[$colMap['course']]))
                ? trim((string) $row[$colMap['course']])
                : '';

            $result[] = [
                'nama_lengkap'   => $namaLengkap,
                'nama_panggilan' => $namaPanggilan,
                'no_hp'          => $noHp,
                'course'         => $course,
            ];
        }

        return $result;
    }

    private function parseXlsxRows(string $filePath): array
    {
        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            return [];
        }

        $sharedStrings = [];
        if (($ssXml = $zip->getFromName('xl/sharedStrings.xml')) !== false) {
            $xml = @simplexml_load_string($ssXml);
            if ($xml && isset($xml->si)) {
                foreach ($xml->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string) $si->t;
                    } elseif (isset($si->r)) {
                        $text = '';
                        foreach ($si->r as $run) {
                            $text .= (string) ($run->t ?? '');
                        }
                        $sharedStrings[] = $text;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            return [];
        }

        $xml = @simplexml_load_string($sheetXml);
        if (!$xml || !isset($xml->sheetData->row)) {
            return [];
        }

        $rows = [];
        foreach ($xml->sheetData->row as $row) {
            $rowData = [];
            $maxCol  = 0;
            foreach ($row->c as $cell) {
                $ref = (string) $cell['r']; // misal A1, B1, C1
                $colLetters = preg_replace('/[0-9]/', '', $ref);
                $colIdx = $this->columnLettersToIndex($colLetters);
                $maxCol = max($maxCol, $colIdx);

                $type = (string) $cell['t'];
                $val  = '';
                if ($type === 's') {
                    $ssIdx = (int) ($cell->v ?? 0);
                    $val   = $sharedStrings[$ssIdx] ?? '';
                } elseif ($type === 'inlineStr') {
                    $val = (string) ($cell->is->t ?? '');
                } else {
                    $val = (string) ($cell->v ?? '');
                }
                $rowData[$colIdx] = $val;
            }

            if (!empty($rowData)) {
                $normalizedRow = [];
                for ($c = 0; $c <= max($maxCol, 4); $c++) {
                    $normalizedRow[$c] = $rowData[$c] ?? '';
                }
                $rows[] = $normalizedRow;
            }
        }

        return $rows;
    }

    private function columnLettersToIndex(string $letters): int
    {
        $letters = strtoupper($letters);
        $index   = 0;
        $len     = strlen($letters);
        for ($i = 0; $i < $len; $i++) {
            $index = $index * 26 + (ord($letters[$i]) - ord('A') + 1);
        }
        return max(0, $index - 1);
    }

    private function parseCsvRows(string $filePath): array
    {
        $rows = [];
        if (($handle = @fopen($filePath, 'r')) === false) {
            return [];
        }

        $firstLine = fgets($handle);
        rewind($handle);
        $delimiter = ',';
        if ($firstLine !== false) {
            if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
                $delimiter = ';';
            } elseif (substr_count($firstLine, "\t") > substr_count($firstLine, ',')) {
                $delimiter = "\t";
            }
        }

        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (!empty(array_filter($data, fn($v) => trim((string) $v) !== ''))) {
                $rows[] = array_values($data);
            }
        }
        fclose($handle);

        return $rows;
    }
}
