<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\TeacherAttendance;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function calendar()
    {
        $schools = School::withCount('students')->orderBy('name')->get();

        $studentDates = StudentAttendance::selectRaw('attendance_date, count(*) as total')
            ->groupBy('attendance_date')
            ->get();

        $events = [];
        foreach ($studentDates as $row) {
            $events[] = [
                'title' => $row->total . ' Siswa Diabsen',
                'start' => $row->attendance_date,
                'backgroundColor' => '#4f46e5',
                'borderColor' => '#4338ca',
                'url' => route('attendance.date', ['date' => $row->attendance_date]),
            ];
        }

        return view('attendance.calendar', compact('schools', 'events'));
    }

    public function showByDate(Request $request, string $date)
    {
        $schools = School::orderBy('name')->get();
        $selectedSchoolId = $request->get('school_id', $schools->first()?->id);
        $selectedSchool = School::find($selectedSchoolId);

        $students = collect();
        $existingStudentAttendances = [];
        $existingTeacherAttendance = null;

        $teachers = auth()->user()->role === 'admin'
            ? User::where('role', 'guru')->orWhere('id', auth()->id())->orderBy('name')->get()
            : collect([auth()->user()]);

        $selectedTeacherId = $request->get('teacher_id', auth()->id());

        if ($selectedSchool) {
            $students = Student::where('school_id', $selectedSchool->id)->orderBy('name')->get();

            $existingStudentAttendances = StudentAttendance::where('attendance_date', $date)
                ->whereIn('student_id', $students->pluck('id'))
                ->pluck('status', 'student_id')
                ->toArray();

            $existingTeacherAttendance = TeacherAttendance::where('attendance_date', $date)
                ->where('school_id', $selectedSchool->id)
                ->where('teacher_id', $selectedTeacherId)
                ->first();
        }

        return view('attendance.date', compact(
            'date',
            'schools',
            'selectedSchool',
            'students',
            'existingStudentAttendances',
            'existingTeacherAttendance',
            'teachers',
            'selectedTeacherId'
        ));
    }

    public function storeByDate(Request $request, string $date)
    {
        $data = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'required|exists:users,id',
            'teacher_status' => 'required|in:hadir,izin,sakit,alfa',
            'topic' => 'nullable|string|max:500',
            'students' => 'nullable|array',
            'students.*' => 'in:hadir,izin,sakit,alfa',
        ]);

        TeacherAttendance::updateOrCreate(
            [
                'teacher_id' => $data['teacher_id'],
                'school_id' => $data['school_id'],
                'attendance_date' => $date,
            ],
            [
                'status' => $data['teacher_status'],
                'topic' => $data['topic'] ?? null,
            ]
        );

        if (!empty($data['students'])) {
            foreach ($data['students'] as $studentId => $status) {
                StudentAttendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'attendance_date' => $date,
                    ],
                    [
                        'teacher_id' => $data['teacher_id'],
                        'status' => $status,
                    ]
                );
            }
        }

        return redirect()->route('attendance.date', [
            'date' => $date,
            'school_id' => $data['school_id'],
            'teacher_id' => $data['teacher_id'],
        ])->with('success', 'Absensi guru dan murid tanggal ' . $date . ' berhasil disimpan!');
    }
}
