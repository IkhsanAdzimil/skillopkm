<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@skillo.id'],
            [
                'name' => 'Admin Skillo Academy',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'guru@skillo.id'],
            [
                'name' => 'Captain (Guru Skillo)',
                'password' => Hash::make('password123'),
                'role' => 'guru',
            ]
        );

        $s1 = School::firstOrCreate(
            ['name' => 'Sekolah Alam Bintaro'],
            ['address' => 'Bintaro Sektor 9, Tangerang Selatan']
        );

        $s2 = School::firstOrCreate(
            ['name' => 'SMPN 1 Pamulang'],
            ['address' => 'Pamulang, Tangerang Selatan']
        );

        Student::firstOrCreate(['school_id' => $s1->id, 'name' => 'Ahmad Fauzan'], ['class_grade' => 'Kelas 7A']);
        Student::firstOrCreate(['school_id' => $s1->id, 'name' => 'Nabila Putri'], ['class_grade' => 'Kelas 7B']);
        Student::firstOrCreate(['school_id' => $s2->id, 'name' => 'Rizky Pratama'], ['class_grade' => 'Kelas 8A']);
    }
}
