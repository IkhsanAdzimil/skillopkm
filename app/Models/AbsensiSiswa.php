<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiSiswa extends Model
{
    use HasFactory;

    protected $table = 'absensi_siswa';

    protected $fillable = [
        'pertemuan_id',
        'siswa_id',
        'status',
        'notes_1',
        'notes_2',
    ];

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class, 'pertemuan_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function getTanggalAttribute()
    {
        return $this->pertemuan?->tanggal;
    }

    public function getSesiAttribute()
    {
        return $this->pertemuan?->sesi;
    }

    public function getSekolahAttribute()
    {
        return $this->pertemuan?->sesi?->sekolah;
    }
}
