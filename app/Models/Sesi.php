<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    protected $table = 'sesi';

    protected $fillable = ['sekolah_id', 'nama_sesi', 'tingkatan'];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function guru()
    {
        return $this->belongsToMany(User::class, 'sesi_guru', 'sesi_id', 'guru_id')
                    ->withTimestamps();
    }

    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'sesi_siswa', 'sesi_id', 'siswa_id')
                    ->withPivot('course')
                    ->withTimestamps();
    }

    public function pertemuan()
    {
        return $this->hasMany(Pertemuan::class, 'sesi_id');
    }

    public function absensiSiswa()
    {
        return $this->hasManyThrough(AbsensiSiswa::class, Pertemuan::class, 'sesi_id', 'pertemuan_id');
    }
}
