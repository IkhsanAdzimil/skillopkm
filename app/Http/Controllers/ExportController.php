<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\Sekolah;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    /**
     * Export rekap absensi ke Excel atau PDF.
     * Digunakan oleh Admin (semua sekolah & sesi) dan Guru (sesi yang diampu).
     */
    public function export(Request $request)
    {
        $request->validate([
            'format'        => 'required|in:excel,pdf',
            'sekolah_id'    => 'nullable|exists:sekolah,id',
            'sesi_id'       => 'nullable|exists:sesi,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
            'periode'       => 'nullable|in:mingguan,bulanan',
        ]);

        $user      = auth()->user();
        $format    = $request->format;
        $tglMulai  = $request->tanggal_mulai;
        $tglAkhir  = $request->tanggal_akhir;
        $sekolahId = $request->sekolah_id;
        $sesiId    = $request->sesi_id;

        $guruSesiIds = collect();
        if ($user->role === 'guru') {
            $guruSesiIds = $user->sesi()->pluck('sesi.id');
        }

        $query = AbsensiSiswa::with([
                'pertemuan.sesi.sekolah',
                'pertemuan.sesi.guru',
                'pertemuan.absensiGuru.guru',
                'siswa',
            ])
            ->whereHas('pertemuan', function ($q) use ($tglMulai, $tglAkhir, $sekolahId, $sesiId, $guruSesiIds, $user) {
                $q->whereBetween('tanggal', [$tglMulai, $tglAkhir]);

                if ($user->role === 'guru') {
                    if ($guruSesiIds->isEmpty()) {
                        $q->whereRaw('1 = 0');
                    } elseif ($sesiId && $guruSesiIds->contains($sesiId)) {
                        $q->where('sesi_id', $sesiId);
                    } else {
                        $q->whereIn('sesi_id', $guruSesiIds);
                    }
                } else {
                    if ($sesiId) {
                        $q->where('sesi_id', $sesiId);
                    }
                    if ($sekolahId) {
                        $q->whereHas('sesi', fn($sq) => $sq->where('sekolah_id', $sekolahId));
                    }
                }
            })
            ->join('pertemuan', 'absensi_siswa.pertemuan_id', '=', 'pertemuan.id')
            ->orderBy('pertemuan.tanggal', 'asc')
            ->select('absensi_siswa.*');

        $rows = $query->get();

        $sekolahList = Sekolah::all()->keyBy('id');
        $filename    = 'Rekap_Absensi_Skillo_' . $tglMulai . '_sd_' . $tglAkhir;

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.pdf', compact('rows', 'sekolahList', 'tglMulai', 'tglAkhir'))
                      ->setPaper('a4', 'landscape');
            return $pdf->download($filename . '.pdf');
        }

        $html = view('exports.excel', compact('rows', 'sekolahList', 'tglMulai', 'tglAkhir'))->render();

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '.xls"');
    }
}
