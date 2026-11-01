<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    protected $fillable = ['teacher_id', 'school_id', 'attendance_date', 'status', 'topic'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
