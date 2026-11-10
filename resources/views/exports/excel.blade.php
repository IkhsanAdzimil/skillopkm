<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h3>REKAP ABSENSI SKILLO ACADEMY</h3>
    <p>Periode: {{ $tglMulai }} s/d {{ $tglAkhir }} | Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;width:100%;font-family:Arial;font-size:11px;">
        <thead>
            <tr style="background:#070B1F;color:#fff;">
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Sekolah</th>
                <th>Sesi</th>
                <th>Tingkatan / Course</th>
                <th>Guru Pengampu (Status)</th>
                <th>Nama Lengkap Siswa</th>
                <th>Nama Panggilan</th>
                <th>NIM</th>
                <th>Status Kehadiran</th>
                <th>Catatan 1 (Tingkatan/Sesi)</th>
                <th>Catatan 2 (Progres/Sesi Pengganti)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $i => $row)
            @php
                $pertemuan = $row->pertemuan;
                $sesi      = $pertemuan?->sesi;
                $sekolah   = $sesi?->sekolah;
                $guruStatusMap = $pertemuan ? $pertemuan->absensiGuru->keyBy('guru_id') : collect();
                $guruTexts = [];
                if ($sesi && $sesi->guru) {
                    foreach ($sesi->guru as $g) {
                        $stGuru = $guruStatusMap[$g->id]->status ?? 'hadir';
                        $guruTexts[] = $g->name . ' (' . ($stGuru === 'hadir' ? 'Hadir' : 'Tidak Hadir') . ')';
                    }
                }
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $pertemuan ? \Carbon\Carbon::parse($pertemuan->tanggal)->format('d/m/Y') : '-' }}</td>
                <td>{{ $sekolah?->nama_sekolah ?? '-' }}</td>
                <td>{{ $sesi?->nama_sesi ?? '-' }}</td>
                <td>{{ $sesi?->tingkatan ?? '-' }}</td>
                <td>{{ !empty($guruTexts) ? implode(', ', $guruTexts) : '-' }}</td>
                <td><strong>{{ $row->siswa?->nama }}</strong></td>
                <td>{{ $row->siswa?->nama_panggilan ?? '-' }}</td>
                <td>{{ $row->siswa?->nim }}</td>
                <td style="font-weight:bold;text-transform:uppercase;">{{ $row->status }}</td>
                <td>{{ $row->notes_1 ?? '-' }}</td>
                <td>{{ $row->notes_2 ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
