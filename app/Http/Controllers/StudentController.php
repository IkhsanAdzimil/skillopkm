<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'class_grade' => 'nullable|string|max:100',
        ]);

        Student::create($data);

        return back()->with('success', 'Murid "' . $data['name'] . '" berhasil ditambahkan!');
    }
}
