@extends('layouts.skillo')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-600">
        <p class="text-sm text-gray-500">Total Sekolah Mitra</p>
        <p class="text-3xl font-extrabold">{{ $schools->count() }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-emerald-600">
        <p class="text-sm text-gray-500">Total Guru Skillo</p>
        <p class="text-3xl font-extrabold">{{ $teachers->count() }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-500">
        <p class="text-sm text-gray-500">Total Murid Ekskul</p>
        <p class="text-3xl font-extrabold">{{ $totalStudents }}</p>
    </div>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 border-b pb-3">
        <div>
            <h2 class="text-lg font-bold">Rekapitulasi & Tarik ke Excel</h2>
            <p class="text-sm text-gray-500">Unduh laporan absensi siswa atau guru menuju file Excel (.xls).</p>
        </div>
        <div class="flex space-x-2 mt-2 md:mt-0">
            <a href="{{ route('admin.recap', ['period' => 'weekly']) }}" class="bg-indigo-50 text-indigo-700 px-3 py-2 rounded text-xs font-bold">Rekap Mingguan</a>
            <a href="{{ route('admin.recap', ['period' => 'monthly']) }}" class="bg-indigo-600 text-white px-3 py-2 rounded text-xs font-bold">Rekap Bulanan &rarr;</a>
        </div>
    </div>
    <form action="{{ route('admin.export') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        <div>
            <label class="block text-xs font-bold uppercase mb-1">Jenis Rekap</label>
            <select name="type" class="w-full border rounded p-2 text-sm">
                <option value="student">Absensi Murid</option>
                <option value="teacher">Absensi Guru</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase mb-1">Sekolah</label>
            <select name="school_id" class="w-full border rounded p-2 text-sm">
                <option value="">Semua Sekolah</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ now()->startOfMonth()->format('Y-m-d') }}" class="w-full border rounded p-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ now()->endOfMonth()->format('Y-m-d') }}" class="w-full border rounded p-2 text-sm">
        </div>
        <div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded text-sm cursor-pointer">
                Download Excel (.xls)
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="font-bold mb-4 border-b pb-2">1. Tambah Sekolah</h3>
        <form action="{{ route('admin.schools.store') }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="name" required placeholder="Nama Sekolah" class="w-full border rounded p-2 text-sm">
            <input type="text" name="address" placeholder="Alamat Sekolah" class="w-full border rounded p-2 text-sm">
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded text-sm font-bold cursor-pointer">+ Simpan Sekolah</button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="font-bold mb-4 border-b pb-2">2. Buat Akun Guru</h3>
        <form action="{{ route('admin.teachers.store') }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="name" required placeholder="Nama Guru" class="w-full border rounded p-2 text-sm">
            <input type="email" name="email" required placeholder="Email Login Guru" class="w-full border rounded p-2 text-sm">
            <input type="password" name="password" required placeholder="Password (min. 6 karakter)" class="w-full border rounded p-2 text-sm">
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded text-sm font-bold cursor-pointer">+ Buat Akun Guru</button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="font-bold mb-4 border-b pb-2">3. Input Murid Baru</h3>
        <form action="{{ route('students.store') }}" method="POST" class="space-y-3">
            @csrf
            <select name="school_id" required class="w-full border rounded p-2 text-sm bg-white">
                <option value="">-- Pilih Sekolah --</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                @endforeach
            </select>
            <input type="text" name="name" required placeholder="Nama Murid" class="w-full border rounded p-2 text-sm">
            <input type="text" name="class_grade" placeholder="Kelas (Contoh: 7A)" class="w-full border rounded p-2 text-sm">
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded text-sm font-bold cursor-pointer">+ Tambah Murid</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="font-bold mb-4">Daftar Sekolah & Jumlah Murid</h3>
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50 border-b"><th class="p-3">Sekolah</th><th class="p-3">Alamat</th><th class="p-3">Murid</th></tr>
            </thead>
            <tbody>
                @foreach($schools as $school)
                <tr class="border-b">
                    <td class="p-3 font-semibold">{{ $school->name }}</td>
                    <td class="p-3 text-gray-500">{{ $school->address ?? '-' }}</td>
                    <td class="p-3 font-bold text-indigo-600">{{ $school->students_count }} Siswa</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="font-bold mb-4">Daftar Akun Guru</h3>
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50 border-b"><th class="p-3">Nama Guru</th><th class="p-3">Email</th></tr>
            </thead>
            <tbody>
                @foreach($teachers as $teacher)
                <tr class="border-b">
                    <td class="p-3 font-semibold">{{ $teacher->name }}</td>
                    <td class="p-3 text-gray-600">{{ $teacher->email }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
