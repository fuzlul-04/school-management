<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::latest()->paginate(10);
        return view('backend.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('backend.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'qualification' => 'nullable|string|max:255',
            'join_date' => 'required|date',
        ]);

        $teacherId = 'TEA-' . strtoupper(Str::random(6));

        $user = \App\Models\User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => strtolower($request->first_name . '.' . $request->last_name . '@teacher.school'),
            'password' => bcrypt('password'),
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'employee_id' => $teacherId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'phone' => $request->phone,
            'address' => $request->address,
            'qualification' => $request->qualification,
            'join_date' => $request->join_date,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher created successfully.');
    }

    public function show(Teacher $teacher)
    {
        $teacher->load('subjects');
        return view('backend.teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        return view('backend.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'qualification' => 'nullable|string|max:255',
            'join_date' => 'required|date',
        ]);

        $teacher->update($request->all());

        if ($teacher->user) {
            $teacher->user->update([
                'name' => $request->first_name . ' ' . $request->last_name,
            ]);
        }

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->user) {
            $teacher->user->delete();
        }
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
