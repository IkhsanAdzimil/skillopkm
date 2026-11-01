<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['school_id', 'name', 'class_grade'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function attendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }
}
