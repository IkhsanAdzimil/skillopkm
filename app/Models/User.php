<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'no_hp',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function sesi()
    {
        return $this->belongsToMany(Sesi::class, 'sesi_guru', 'guru_id', 'sesi_id')
                    ->withTimestamps();
    }

    public function absensiGuru()
    {
        return $this->hasMany(AbsensiGuru::class, 'guru_id');
    }
}
