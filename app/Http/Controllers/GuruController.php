<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\AbsensiSiswa;
use App\Models\Pertemuan;
use App\Models\Sesi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function dashboard(Request $request)
    {
        $guru = auth()->user();

        // Ambil seluruh sesi yang diampu oleh guru ini
        $daftarSesi = $guru->sesi()
            ->with([
                'sekolah',
                'guru'  => fn($q) => $q->orderBy('name'),
                'siswa' => fn($q) => $q->orderBy('nama'),
            ])
            ->orderBy('sekolah_id')
            ->orderBy('id')
            ->get();

        // Validasi input tanggal untuk form jurnal harian
        $rawTanggal = $request->query('tanggal');
        try {
            $tanggal = $rawTanggal ? Carbon::parse($rawTanggal)->format('Y-m-d') : now()->format('Y-m-d');
        } catch (\Exception $e) {
            $tanggal = now()->format('Y-m-d');
        }

        // Filter sesi untuk form jurnal harian
        $selectedSesiId = $request->query('sesi_id', 'semua');

        if ($selectedSesiId !== 'semua' && is_numeric($selectedSesiId)) {
            $sesiDitampilkan = $daftarSesi->where('id', (int) $selectedSesiId)->values();
            if ($sesiDitampilkan->isEmpty()) {
                $sesiDitampilkan = $daftarSesi;
                $selectedSesiId  = 'semua';
            }
        } else {
            $sesiDitampilkan = $daftarSesi;
            $selectedSesiId  = 'semua';
        }

        $pertemuanMap    = [];
        $absensiSiswaMap = [];
        $absensiGuruMap  = [];

        foreach ($daftarSesi as $s) {
            $pertemuan = Pertemuan::where('sesi_id', $s->id)
                ->where('tanggal', $tanggal)
                ->with(['absensiSiswa', 'absensiGuru'])
                ->first();

            $pertemuanMap[$s->id]    = $pertemuan;
            $absensiSiswaMap[$s->id] = $pertemuan ? $pertemuan->absensiSiswa->keyBy('siswa_id') : collect();
            $absensiGuruMap[$s->id]  = $pertemuan ? $pertemuan->absensiGuru->keyBy('guru_id') : collect();
        }

        // === MULAI TAMBAHAN: LIST RIWAYAT MENGAJAR GURU ===
        try {
            $riwayatMulai = $request->filled('riwayat_mulai')
                ? Carbon::parse($request->query('riwayat_mulai'))->format('Y-m-d')
                : now()->startOfMonth()->format('Y-m-d');
        } catch (\Exception $e) {
            $riwayatMulai = now()->startOfMonth()->format('Y-m-d');
        }

        try {
            $riwayatAkhir = $request->filled('riwayat_akhir')
                ? Carbon::parse($request->query('riwayat_akhir'))->format('Y-m-d')
                : now()->endOfMonth()->format('Y-m-d');
        } catch (\Exception $e) {
            $riwayatAkhir = now()->endOfMonth()->format('Y-m-d');
        }

        $riwayatSesiId = $request->query('riwayat_sesi_id', '');
        $sesiIdsGuru   = $daftarSesi->pluck('id');

        $riwayatQuery = Pertemuan::with([
            'sesi.sekolah',
            'sesi.guru',
            'sesi.siswa',
            'absensiGuru.guru',
            'absensiSiswa.siswa',
        ])->where(function ($q) use ($sesiIdsGuru, $guru) {
            $q->whereIn('sesi_id', $sesiIdsGuru)
              ->orWhereHas('absensiGuru', fn($ag) => $ag->where('guru_id', $guru->id));
        })->whereBetween('tanggal', [$riwayatMulai, $riwayatAkhir]);

        if (!empty($riwayatSesiId) && is_numeric($riwayatSesiId)) {
            $riwayatQuery->where('sesi_id', (int) $riwayatSesiId);
        }

        $riwayatPertemuanList = $riwayatQuery
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // Statistik ringkas riwayat mengajar pada periode terpilih
        $riwayatStats = [
            'total_pertemuan'  => $riwayatPertemuanList->count(),
            'guru_hadir'       => 0,
            'guru_tidak_hadir' => 0,
            'total_catatan'    => 0,
        ];

       foreach ($riwayatPertemuanList as $pt) {
            $recGuruSaya = $pt->absensiGuru->firstWhere('guru_id', $guru->id);
            $statusSaya  = $recGuruSaya ? $recGuruSaya->status : 'hadir';

            if ($statusSaya === 'hadir') {
                $riwayatStats['guru_hadir']++;
            } else {
                $riwayatStats['guru_tidak_hadir']++;
            }

            foreach ($pt->absensiSiswa as $abs) {
                if (!empty($abs->notes_1) || !empty($abs->notes_2)) {
                    $riwayatStats['total_catatan']++;
                }
            } // <-- 1. Tutup foreach ($pt->absensiSiswa)
        } // <-- 2. Tutup foreach ($riwayatPertemuanList)
        // JANGAN ADA '}' LAGI DI SINI! Langsung masuk ke return view:

        return view('guru.dashboard', compact(
            'daftarSesi',
            'sesiDitampilkan',
            'selectedSesiId',
            'tanggal',
            'pertemuanMap',
            'absensiSiswaMap',
            'absensiGuruMap',
            'riwayatMulai',
            'riwayatAkhir',
            'riwayatSesiId',
            'riwayatPertemuanList',
            'riwayatStats'
        ));
    } // <-- 3. Baru tutup public function dashboard() di sini (tepat di atas public function simpanAbsensi)

    public function simpanAbsensi(Request $request)
    {
        $data = $request->validate([
            'sesi_id'        => 'required|exists:sesi,id',
            'tanggal'        => 'required|date',
            'materi'         => 'nullable|string|max:255',
            'catatan_guru'   => 'nullable|string|max:1000',
            'absensi_guru'   => 'nullable|array',
            'absensi_guru.*' => 'in:hadir,tidak_hadir',
            'absensi'        => 'nullable|array',
            'absensi.*'      => 'in:hadir,sakit,izin,alfa',
            'notes_1'        => 'nullable|array',
            'notes_1.*'      => 'nullable|string|max:500',
            'notes_2'        => 'nullable|array',
            'notes_2.*'      => 'nullable|string|max:500',
            'course'         => 'nullable|array',
            'course.*'       => 'nullable|string|max:255',
        ]);

        // Pastikan guru yang login merupakan salah satu pengampu di sesi tersebut
        $sesi = Sesi::with(['sekolah', 'guru', 'siswa'])
            ->where('id', $data['sesi_id'])
            ->whereHas('guru', fn($q) => $q->where('users.id', auth()->id()))
            ->firstOrFail();

        $absensiGuruInput  = $data['absensi_guru'] ?? [];
        $absensiSiswaInput = $data['absensi']      ?? [];
        $notes1Input       = $data['notes_1']      ?? [];
        $notes2Input       = $data['notes_2']      ?? [];
        $courseInput       = $data['course']       ?? [];

        DB::transaction(function () use ($sesi, $data, $absensiGuruInput, $absensiSiswaInput, $notes1Input, $notes2Input, $courseInput) {
            // 1. Header Pertemuan Sesi pada tanggal terpilih
            $pertemuan = Pertemuan::firstOrCreate([
                'sesi_id' => $sesi->id,
                'tanggal' => $data['tanggal'],
            ]);

            $pertemuan->update([
                'materi'       => $data['materi'] ?? $pertemuan->materi,
                'catatan_guru' => $data['catatan_guru'] ?? $pertemuan->catatan_guru,
            ]);

            // 2. Simpan Absensi Guru Pengampu (tiap guru di sesi ini bisa saling mengabsenkan rekan gurunya)
            foreach ($sesi->guru as $guruPengampu) {
                $statusGuru = $absensiGuruInput[$guruPengampu->id] ?? 'hadir';
                AbsensiGuru::updateOrCreate(
                    [
                        'pertemuan_id' => $pertemuan->id,
                        'guru_id'      => $guruPengampu->id,
                    ],
                    [
                        'status' => $statusGuru,
                    ]
                );
            }

            // 3. Simpan Absensi Siswa beserta 2 Catatan Manual (notes_1 & notes_2)
            foreach ($sesi->siswa as $siswa) {
                if (array_key_exists($siswa->id, $courseInput)) {
                    $valCourse = !empty($courseInput[$siswa->id]) ? trim($courseInput[$siswa->id]) : null;
                    $sesi->siswa()->updateExistingPivot($siswa->id, ['course' => $valCourse]);
                }

                AbsensiSiswa::updateOrCreate(
                    [
                        'pertemuan_id' => $pertemuan->id,
                        'siswa_id'     => $siswa->id,
                    ],
                    [
                        'status'  => $absensiSiswaInput[$siswa->id] ?? 'hadir',
                        'notes_1' => isset($notes1Input[$siswa->id]) && trim($notes1Input[$siswa->id]) !== ''
                            ? trim($notes1Input[$siswa->id])
                            : null,
                        'notes_2' => isset($notes2Input[$siswa->id]) && trim($notes2Input[$siswa->id]) !== ''
                            ? trim($notes2Input[$siswa->id])
                            : null,
                    ]
                );
            }
        });

        $labelSesi = ($sesi->sekolah?->nama_sekolah ?? 'Sekolah') . ' — ' . $sesi->nama_sesi;

        return back()->with('success', 'Presensi guru, absensi murid, dan 2 catatan pada ' . $labelSesi . ' (' . $data['tanggal'] . ') berhasil disimpan!');
    }

    public function simpanCatatanSingle(Request $request)
    {
        $data = $request->validate([
            'sesi_id'  => 'required|exists:sesi,id',
            'siswa_id' => 'required|exists:siswa,id',
            'tanggal'  => 'required|date',
            'notes_1'  => 'nullable|string|max:500',
            'notes_2'  => 'nullable|string|max:500',
            'status'   => 'nullable|in:hadir,sakit,izin,alfa',
        ]);

        $sesi = Sesi::where('id', $data['sesi_id'])
            ->whereHas('guru', fn($q) => $q->where('users.id', auth()->id()))
            ->firstOrFail();

        $pertemuan = Pertemuan::firstOrCreate([
            'sesi_id' => $sesi->id,
            'tanggal' => $data['tanggal'],
        ]);

        // Pastikan guru yang sedang aktif tercatat hadir secara default jika belum ada record absensi guru
        AbsensiGuru::firstOrCreate(
            [
                'pertemuan_id' => $pertemuan->id,
                'guru_id'      => auth()->id(),
            ],
            [
                'status' => 'hadir',
            ]
        );

        $absensi = AbsensiSiswa::where('pertemuan_id', $pertemuan->id)
            ->where('siswa_id', $data['siswa_id'])
            ->first();

        $notes1 = isset($data['notes_1']) && trim($data['notes_1']) !== '' ? trim($data['notes_1']) : null;
        $notes2 = isset($data['notes_2']) && trim($data['notes_2']) !== '' ? trim($data['notes_2']) : null;

        if ($absensi) {
            if ($request->has('notes_1')) {
                $absensi->notes_1 = $notes1;
            }
            if ($request->has('notes_2')) {
                $absensi->notes_2 = $notes2;
            }
            if (!empty($data['status'])) {
                $absensi->status = $data['status'];
            }
            $absensi->save();
        } else {
            AbsensiSiswa::create([
                'pertemuan_id' => $pertemuan->id,
                'siswa_id'     => $data['siswa_id'],
                'status'       => $data['status'] ?? 'hadir',
                'notes_1'      => $notes1,
                'notes_2'      => $notes2,
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }

}
