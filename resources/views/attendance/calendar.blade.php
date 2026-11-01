@extends('layouts.skillo')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-xl shadow-sm h-fit">
        <h3 class="font-bold text-lg mb-2">Tambah Murid Ekskul</h3>
        <p class="text-xs text-gray-500 mb-4">Tambahkan siswa baru jika belum terdaftar di daftar absen sekolah.</p>
        <form action="{{ route('students.store') }}" method="POST" class="space-y-3">
            @csrf
            <select name="school_id" required class="w-full border rounded p-2.5 text-sm bg-white">
                <option value="">-- Pilih Sekolah --</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}">{{ $school->name }} ({{ $school->students_count }} Siswa)</option>
                @endforeach
            </select>
            <input type="text" name="name" required placeholder="Nama Lengkap Murid" class="w-full border rounded p-2.5 text-sm">
            <input type="text" name="class_grade" placeholder="Kelas (Contoh: 8B)" class="w-full border rounded p-2.5 text-sm">
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded text-sm cursor-pointer">
                + Simpan Murid
            </button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm">
        <div class="mb-4 border-b pb-3">
            <h2 class="text-xl font-bold">Kalender Absensi Ekskul Skillo</h2>
            <p class="text-sm text-gray-500">Klik pada <strong>tanggal mana saja</strong> di kalender untuk mengabsen siswa dan guru.</p>
        </div>
        <div id="calendar" class="text-sm"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        locale: 'id',
        height: 'auto',
        events: @json($events),
        dateClick: function(info) {
            window.location.href = '/attendance/' + info.dateStr;
        }
    });
    calendar.render();
});
</script>
@endsection
