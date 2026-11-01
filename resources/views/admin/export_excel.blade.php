<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body>
    <h3>REKAP ABSENSI {{ strtoupper($type) }} SKILLO ACADEMY ({{ $startDate }} s/d {{ $endDate }})</h3>
    <table border="1">
        <thead>
            @if($type === 'teacher')
                <tr><th>No</th><th>Tanggal</th><th>Nama Guru</th><th>Sekolah</th><th>Status</th><th>Materi</th></tr>
            @else
                <tr><th>No</th><th>Tanggal</th><th>Nama Murid</th><th>Kelas</th><th>Sekolah</th><th>Status</th><th>Guru Pengabsen</th></tr>
            @endif
        </thead>
        <tbody>
            @foreach($rows as $i => $row)
                @if($type === 'teacher')
                    <tr><td>{{ $i+1 }}</td><td>{{ $row->attendance_date }}</td><td>{{ $row->teacher?->name }}</td><td>{{ $row->school?->name }}</td><td>{{ strtoupper($row->status) }}</td><td>{{ $row->topic ?? '-' }}</td></tr>
                @else
                    <tr><td>{{ $i+1 }}</td><td>{{ $row->attendance_date }}</td><td>{{ $row->student?->name }}</td><td>{{ $row->student?->class_grade }}</td><td>{{ $row->student?->school?->name }}</td><td>{{ strtoupper($row->status) }}</td><td>{{ $row->teacher?->name }}</td></tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>
</html>
