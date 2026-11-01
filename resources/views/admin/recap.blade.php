@extends('layouts.skillo')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-sm mb-6">
    <div class="flex flex-col md:flex-row justify-between items-center border-b pb-4 mb-4 gap-3">
        <div>
            <h2 class="text-xl font-bold">Rekap Absensi Per Minggu & Per Bulan</h2>
            <p class="text-sm text-gray-500">Periode: <strong>{{ $startDate }}</strong> s/d <strong>{{ $endDate }}</strong></p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.export', ['type' => 'student', 'start_date' => $startDate, 'end_date' => $endDate, 'school_id' => $schoolId]) }}" class="bg-emerald-600 text-white px-4 py-2 rounded text-xs font-bold">Export Excel Murid (.xls)</a>
            <a href="{{ route('admin.export', ['type' => 'teacher', 'start_date' => $startDate, 'end_date' => $endDate, 'school_id' => $schoolId]) }}" class="bg-teal-600 text-white px-4 py-2 rounded text-xs font-bold">Export Excel Guru (.xls)</a>
        </div>
    </div>
    <form action="{{ route('admin.recap') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-xs font-bold uppercase mb-1">Periode Rekap</label>
            <select name="period" class="w-full border rounded p-2 text-sm">
                <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Per Minggu (Mingguan)</option>
                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Per Bulan (Bulanan)</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase mb-1">Tanggal Acuan</label>
            <input type="date" name="date" value="{{ $date }}" class="w-full border rounded p-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase mb-1">Sekolah</label>
            <select name="school_id" class="w-full border rounded p-2 text-sm">
                <option value="">Semua Sekolah</option>
                @foreach($schools as $s)
                    <option value="{{ $s->id }}" {{ (string)$schoolId === (string)$s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded text-sm cursor-pointer">Filter Rekap</button>
    </form>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm mb-6">
    <h3 class="font-bold mb-3">Rekap Absensi Guru ({{ $teacherRecap->count() }})</h3>
    <table class="w-full text-left border-collapse text-sm">
        <thead><tr class="bg-gray-100 border-b"><th class="p-2.5">Tanggal</th><th class="p-2.5">Guru</th><th class="p-2.5">Sekolah</th><th class="p-2.5">Status</th><th class="p-2.5">Materi</th></tr></thead>
        <tbody>
            @foreach($teacherRecap as $r)
            <tr class="border-b"><td class="p-2.5">{{ $r->attendance_date }}</td><td class="p-2.5 font-semibold">{{ $r->teacher?->name }}</td><td class="p-2.5">{{ $r->school?->name }}</td><td class="p-2.5 uppercase font-bold">{{ $r->status }}</td><td class="p-2.5">{{ $r->topic ?? '-' }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm">
    <h3 class="font-bold mb-3">Rekap Absensi Murid ({{ $studentRecap->count() }})</h3>
    <table class="w-full text-left border-collapse text-sm">
        <thead><tr class="bg-gray-100 border-b"><th class="p-2.5">Tanggal</th><th class="p-2.5">Murid</th><th class="p-2.5">Kelas</th><th class="p-2.5">Sekolah</th><th class="p-2.5">Status</th><th class="p-2.5">Pengabsen</th></tr></thead>
        <tbody>
            @foreach($studentRecap as $r)
            <tr class="border-b"><td class="p-2.5">{{ $r->attendance_date }}</td><td class="p-2.5 font-semibold">{{ $r->student?->name }}</td><td class="p-2.5">{{ $r->student?->class_grade }}</td><td class="p-2.5">{{ $r->student?->school?->name }}</td><td class="p-2.5 uppercase font-bold">{{ $r->status }}</td><td class="p-2.5">{{ $r->teacher?->name }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
