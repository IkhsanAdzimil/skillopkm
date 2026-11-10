<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; color: #1a1a1a; }
        h2 { color: #070B1F; font-size: 14px; margin-bottom: 4px; }
        .subtitle { color: #6b7280; font-size: 10px; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead th { background: #070B1F; color: #fff; padding: 7px 8px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: .4px; }
        tbody tr:nth-child(even) { background: #f8faff; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        .status-hadir  { color: #16a34a; font-weight: bold; text-transform: uppercase; }
        .status-sakit  { color: #d97706; font-weight: bold; text-transform: uppercase; }
        .status-izin   { color: #2563eb; font-weight: bold; text-transform: uppercase; }
        .status-alfa   { color: #dc2626; font-weight: bold; text-transform: uppercase; }
        .footer { margin-top: 18px; font-size: 9px; color: #9ca3af; text-align: right; }
        .badge { background: #eef2ff; color: #4f46e5; padding: 2px 5px; border-radius: 4px; font-size: 8.5px; }
    </style>
</head>
<body>
    <h2>REKAP ABSENSI — SKILLO ACADEMY</h2>
    <div class="subtitle">Periode: {{ $tglMulai }} s/d {{ $tglAkhir }} &nbsp;|&nbsp; Dicetak: {{ now()->format('d/m/Y H:i') }}</div>

    @if($rows->isEmpty())
        <p style="color:#6b7280;font-style:italic;">Tidak ada data absensi pada periode ini.</p>
    @else
    <table>
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th style="width:8%">Tanggal</th>
                <th style="width:14%">Sekolah & Sesi</th>
                <th style="width:15%">Guru Pengampu</th>
                <th style="width:16%">Nama Siswa (Panggilan)</th>
                <th style="width:9%">NIM</th>
                <th style="width:7%">Status</th>
                <th style="width:14%">Catatan 1</th>
                <th style="width:14%">Catatan 2</th>
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
                <td>
                    <strong>{{ $sekolah?->nama_sekolah ?? '-' }}</strong><br>
                    <span>{{ $sesi?->nama_sesi ?? '-' }} {{ $sesi?->tingkatan ? '(' . $sesi->tingkatan . ')' : '' }}</span>
                </td>
                <td>{{ !empty($guruTexts) ? implode(', ', $guruTexts) : '-' }}</td>
                <td>
                    <strong>{{ $row->siswa?->nama }}</strong>
                    @if(!empty($row->siswa?->nama_panggilan))
                        <br><span style="color:#6b7280;">({{ $row->siswa->nama_panggilan }})</span>
                    @endif
                </td>
                <td><span class="badge">{{ $row->siswa?->nim }}</span></td>
                <td class="status-{{ $row->status }}">{{ strtoupper($row->status) }}</td>
                <td style="color:#374151">{{ $row->notes_1 ?? '—' }}</td>
                <td style="color:#374151">{{ $row->notes_2 ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        Portal Staff — Skillo Academy &nbsp;|&nbsp; {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
