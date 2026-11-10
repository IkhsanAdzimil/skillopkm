<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\AbsensiGuru;
use App\Models\AbsensiSiswa;
use App\Models\Pertemuan;
use App\Models\Sekolah;
use App\Models\Sesi;
use App\Models\Siswa;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Users ---
        $admin = User::create([
            'name'     => 'Administrator',
            'username' => 'admin_skillo',
            'email'    => 'admin@skillo.id',
            'no_hp'    => '08111000001',
            'role'     => 'admin',
            'password' => Hash::make('password123'),
        ]);

        $guru1 = User::create([
            'name'     => 'Budi Santoso',
            'username' => 'budi_guru',
            'email'    => 'guru@skillo.id',
            'no_hp'    => '08222000002',
            'role'     => 'guru',
            'password' => Hash::make('password123'),
        ]);

        $guru2 = User::create([
            'name'     => 'Siti Rahayu',
            'username' => 'siti_guru',
            'email'    => 'siti@skillo.id',
            'no_hp'    => '08333000003',
            'role'     => 'guru',
            'password' => Hash::make('password123'),
        ]);

        $guru3 = User::create([
            'name'     => 'Rizky Pratama',
            'username' => 'rizky_guru',
            'email'    => 'rizky@skillo.id',
            'no_hp'    => '08444000004',
            'role'     => 'guru',
            'password' => Hash::make('password123'),
        ]);

        // --- Sekolah & Sesi ---
        $sekolah1 = Sekolah::create([
            'nama_sekolah' => 'SD Islam Al-Azhar BSD',
            'deskripsi'    => 'Program ekstrakurikuler Coding & Game Development.',
            'status'       => 'aktif',
        ]);

        $s1_sesi1 = Sesi::create([
            'sekolah_id' => $sekolah1->id,
            'nama_sesi'  => 'Sesi 1',
            'tingkatan'  => 'Course E (Junior Coding)',
        ]);
        $s1_sesi1->guru()->sync([$guru1->id, $guru2->id]); // Multi-guru (2 guru pengampu)

        $s1_sesi2 = Sesi::create([
            'sekolah_id' => $sekolah1->id,
            'nama_sesi'  => 'Sesi 2',
            'tingkatan'  => 'Course F (Game Dev Unity)',
        ]);
        $s1_sesi2->guru()->sync([$guru1->id, $guru3->id]);

        $sekolah2 = Sekolah::create([
            'nama_sekolah' => 'SMPN 1 Tangerang Selatan',
            'deskripsi'    => 'Program ekstrakurikuler UI/UX Design & Web Development.',
            'status'       => 'aktif',
        ]);

        $s2_sesi1 = Sesi::create([
            'sekolah_id' => $sekolah2->id,
            'nama_sesi'  => 'Sesi 1',
            'tingkatan'  => 'Level 1 (UI/UX Fundamental)',
        ]);
        $s2_sesi1->guru()->sync([$guru2->id]);

        $s2_sesi2 = Sesi::create([
            'sekolah_id' => $sekolah2->id,
            'nama_sesi'  => 'Sesi 2',
            'tingkatan'  => 'Level 2 (Web App Prototyping)',
        ]);
        $s2_sesi2->guru()->sync([$guru2->id, $guru3->id]);

        // --- Siswa ---
        $siswaDummy = [
            ['nim' => 'SKL2026001', 'nama' => 'Keano Putra Pratama', 'nama_panggilan' => 'Keano', 'no_hp' => '081234567801'],
            ['nim' => 'SKL2026002', 'nama' => 'Budi Santoso Jr.', 'nama_panggilan' => 'Budi', 'no_hp' => '081234567802'],
            ['nim' => 'SKL2026003', 'nama' => 'Citra Dewi Lestari', 'nama_panggilan' => 'Citra', 'no_hp' => '081234567803'],
            ['nim' => 'SKL2026004', 'nama' => 'Deni Maulana', 'nama_panggilan' => 'Deni', 'no_hp' => '081234567804'],
            ['nim' => 'SKL2026005', 'nama' => 'Eka Putri Maharani', 'nama_panggilan' => 'Eka', 'no_hp' => '081234567805'],
            ['nim' => 'SKL2026006', 'nama' => 'Faris Rahman', 'nama_panggilan' => 'Faris', 'no_hp' => '081234567806'],
            ['nim' => 'SKL2026007', 'nama' => 'Gita Novia', 'nama_panggilan' => 'Gita', 'no_hp' => '081234567807'],
            ['nim' => 'SKL2026008', 'nama' => 'Hendra Wijaya', 'nama_panggilan' => 'Hendra', 'no_hp' => '081234567808'],
        ];

        $siswaObjs = [];
        foreach ($siswaDummy as $s) {
            $siswaObjs[] = Siswa::create($s);
        }

        // Alokasi murid berbeda untuk Sesi 1 dan Sesi 2
        foreach (collect($siswaObjs)->take(4) as $s) {
            $s1_sesi1->siswa()->attach($s->id, ['course' => 'Course E']);
        }
        foreach (collect($siswaObjs)->slice(4, 4) as $s) {
            $s1_sesi2->siswa()->attach($s->id, ['course' => 'Course F']);
        }

        // Contoh Pertemuan & Absensi Minggu Ini
        $pertemuan1 = Pertemuan::create([
            'sesi_id' => $s1_sesi1->id,
            'tanggal' => now()->format('Y-m-d'),
            'materi'  => 'Nested Loops & Sprite Animation di Course E',
        ]);

        AbsensiGuru::create(['pertemuan_id' => $pertemuan1->id, 'guru_id' => $guru1->id, 'status' => 'hadir']);
        AbsensiGuru::create(['pertemuan_id' => $pertemuan1->id, 'guru_id' => $guru2->id, 'status' => 'hadir']);

        AbsensiSiswa::create([
            'pertemuan_id' => $pertemuan1->id,
            'siswa_id'     => $siswaObjs[0]->id,
            'status'       => 'hadir',
            'notes_1'      => 'Keano Sesi 1 Course E - Lesson 8 selesai',
            'notes_2'      => 'Paham logika perulangan dengan sangat baik',
        ]);

        AbsensiSiswa::create([
            'pertemuan_id' => $pertemuan1->id,
            'siswa_id'     => $siswaObjs[1]->id,
            'status'       => 'izin',
            'notes_1'      => 'Budi Sesi 1 Course E - Izin acara keluarga',
            'notes_2'      => 'Akan mengambil sesi pengganti di Sesi 2 minggu depan',
        ]);
    }
}
