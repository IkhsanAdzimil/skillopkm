<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertemuan extends Model
{
    use HasFactory;

    protected $table = 'pertemuan';

    protected $fillable = [
        'sesi_id',
        'tanggal',
        'materi',
        'catatan_guru',
    ];

    public function sesi()
    {
        return $this->belongsTo(Sesi::class, 'sesi_id');
    }

    public function absensiSiswa()
    {
        return $this->hasMany(AbsensiSiswa::class, 'pertemuan_id');
    }

    public function absensiGuru()
    {
        return $this->hasMany(AbsensiGuru::class, 'pertemuan_id');
    }
}
