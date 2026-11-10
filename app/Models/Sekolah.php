<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $table = 'sekolah';

    protected $fillable = ['nama_sekolah', 'deskripsi', 'status'];

    public function sesi()
    {
        return $this->hasMany(Sesi::class, 'sekolah_id');
    }
}
