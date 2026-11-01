@extends('layouts.skillo')

@section('content')
<div class="mb-4 flex items-center justify-between">
    <div>
        <a href="{{ route('guru.calendar') }}" class="text-sm text-indigo-600 hover:underline font-semibold">&larr; Kembali ke Kalender</a>
        <h2 class="text-2xl font-extrabold mt-1">Absensi Tanggal: <span class="text-indigo-600">{{ $date }}</span></h2>
    </div>
    <input type="date" value="{{ $date }}" onchange="window.location.href='/attendance/' + this.value" class="border rounded px-3 py-2 text-sm bg-white">
</div>

<div class="bg-white p-5 rounded-xl shadow-sm mb-6">
    <form action="{{ route('attendance.date', ['date' => $date]) }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold uppercase mb-1">Pilih Sekolah</label>
            <select name="school_id" onchange="this.form.submit()" class="w-full border rounded p-2.5 text-sm font-semibold">
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ $selectedSchool && $selectedSchool->id === $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        @if(auth()->user()->role === 'admin')
        <div>
            <label class="block text-xs font-bold uppercase mb-1">Guru Pengajar</label>
            <select name="teacher_id" onchange="this.form.submit()" class="w-full border rounded p-2.5 text-sm">
                @foreach($teachers as $t)
                    <option value="{{ $t->id }}" {{ (int)$selectedTeacherId === (int)$t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </form>
</div>

@if($selectedSchool)
<form action="{{ route('attendance.store', ['date' => $date]) }}" method="POST">
    @csrf
    <input type="hidden" name="school_id" value="{{ $selectedSchool->id }}">
    <input type="hidden" name="teacher_id" value="{{ $selectedTeacherId }}">

    <div class="bg-white p-6 rounded-xl shadow-sm mb-6">
        <h3 class="font-bold text-lg mb-4 border-b pb-2">1. Kehadiran Guru Pengajar</h3>
        @php $tStatus = $existingTeacherAttendance->status ?? 'hadir'; @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex space-x-4 items-center">
                @foreach(['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alfa' => 'Alfa'] as $val => $label)
                    <label class="flex items-center space-x-1.5 cursor-pointer">
                        <input type="radio" name="teacher_status" value="{{ $val }}" {{ $tStatus === $val ? 'checked' : '' }}>
                        <span class="text-sm font-semibold">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <input type="text" name="topic" value="{{ $existingTeacherAttendance->topic ?? '' }}" placeholder="Materi yang diajarkan hari ini..." class="w-full border rounded p-2 text-sm">
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="font-bold text-lg mb-4 border-b pb-2">2. Daftar Absensi Murid ({{ $selectedSchool->name }})</h3>
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50 border-b"><th class="p-3">No</th><th class="p-3">Nama Murid</th><th class="p-3">Kelas</th><th class="p-3 text-center">Status Kehadiran</th></tr>
            </thead>
            <tbody>
                @foreach($students as $i => $student)
                @php $sStatus = $existingStudentAttendances[$student->id] ?? 'hadir'; @endphp
                <tr class="border-b">
                    <td class="p-3">{{ $i + 1 }}</td>
                    <td class="p-3 font-semibold">{{ $student->name }}</td>
                    <td class="p-3">{{ $student->class_grade ?? '-' }}</td>
                    <td class="p-3 text-center space-x-3">
                        @foreach(['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alfa' => 'Alfa'] as $val => $label)
                            <label class="inline-flex items-center space-x-1 cursor-pointer">
                                <input type="radio" name="students[{{ $student->id }}]" value="{{ $val }}" {{ $sStatus === $val ? 'checked' : '' }}>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2.5 rounded-lg shadow cursor-pointer">
                Simpan Absensi
            </button>
        </div>
    </div>
</form>
@endif
@endsection
