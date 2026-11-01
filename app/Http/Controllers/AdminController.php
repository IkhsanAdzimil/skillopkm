<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\TeacherAttendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $schools = School::withCount('students')->latest()->get();
        $teachers = User::where('role', 'guru')->latest()->get();
        $totalStudents = Student::count();

        return view('admin.dashboard', compact('schools', 'teachers', 'totalStudents'));
    }

    public function storeTeacher(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'guru',
        ]);

        return back()->with('success', 'Akun Guru "' . $data['name'] . '" berhasil dibuat!');
    }

    public function deleteTeacher(User $user)
    {
        if ($user->role === 'guru') {
            $user->delete();
            return back()->with('success', 'Akun Guru berhasil dihapus.');
        }
        return back();
    }

    public function storeSchool(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        School::create($data);

        return back()->with('success', 'Sekolah "' . $data['name'] . '" berhasil ditambahkan!');
    }

    public function deleteSchool(School $school)
    {
        $school->delete();
        return back()->with('success', 'Data sekolah beserta muridnya berhasil dihapus.');
    }

    public function recap(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $date = $request->get('date', now()->format('Y-m-d'));
        $schoolId = $request->get('school_id');

        $carbonDate = Carbon::parse($date);

        if ($period === 'weekly') {
            $startDate = $carbonDate->copy()->startOfWeek()->format('Y-m-d');
            $endDate = $carbonDate->copy()->endOfWeek()->format('Y-m-d');
        } else {
            $startDate = $carbonDate->copy()->startOfMonth()->format('Y-m-d');
            $endDate = $carbonDate->copy()->endOfMonth()->format('Y-m-d');
        }

        $schools = School::orderBy('name')->get();

        $studentRecap = StudentAttendance::with(['student.school', 'teacher'])
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->when($schoolId, function ($q) use ($schoolId) {
                $q->whereHas('student', fn($query) => $query->where('school_id', $schoolId));
            })
            ->orderBy('attendance_date', 'desc')
            ->get();

        $teacherRecap = TeacherAttendance::with(['teacher', 'school'])
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->orderBy('attendance_date', 'desc')
            ->get();

        return view('admin.recap', compact(
            'studentRecap',
            'teacherRecap',
            'schools',
            'period',
            'date',
            'schoolId',
            'startDate',
            'endDate'
        ));
    }

    public function exportExcel(Request $request)
    {
        $type = $request->get('type', 'student');
        $schoolId = $request->get('school_id');
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        if ($type === 'teacher') {
            $rows = TeacherAttendance::with(['teacher', 'school'])
                ->whereBetween('attendance_date', [$startDate, $endDate])
                ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
                ->orderBy('attendance_date', 'asc')
                ->get();
        } else {
            $rows = StudentAttendance::with(['student.school', 'teacher'])
                ->whereBetween('attendance_date', [$startDate, $endDate])
                ->when($schoolId, function ($q) use ($schoolId) {
                    $q->whereHas('student', fn($query) => $query->where('school_id', $schoolId));
                })
                ->orderBy('attendance_date', 'asc')
                ->get();
        }

        $filename = "Rekap_Absensi_" . ucfirst($type) . "_Skillo_{$startDate}_sd_{$endDate}.xls";
        $html = view('admin.export_excel', compact('rows', 'type', 'startDate', 'endDate'))->render();

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
