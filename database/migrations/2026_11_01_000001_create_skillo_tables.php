<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->timestamps();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('name');
            $table->string('class_grade')->nullable();
            $table->timestamps();
        });

        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->date('attendance_date');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa'])->default('hadir');
            $table->timestamps();

            $table->unique(['student_id', 'attendance_date']);
        });

        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->date('attendance_date');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa'])->default('hadir');
            $table->text('topic')->nullable();
            $table->timestamps();

            $table->unique(['teacher_id', 'school_id', 'attendance_date'], 'teacher_school_date_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_attendances');
        Schema::dropIfExists('student_attendances');
        Schema::dropIfExists('students');
        Schema::dropIfExists('schools');
    }
};
