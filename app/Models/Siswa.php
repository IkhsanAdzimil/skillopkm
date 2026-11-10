<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = ['nim', 'nama', 'nama_panggilan', 'no_hp', 'status'];

    public function sesi()
    {
        return $this->belongsToMany(Sesi::class, 'sesi_siswa', 'siswa_id', 'sesi_id')
                    ->withPivot('course')
                    ->withTimestamps();
    }

    public function absensiSiswa()
    {
        return $this->hasMany(AbsensiSiswa::class, 'siswa_id');
    }
}
