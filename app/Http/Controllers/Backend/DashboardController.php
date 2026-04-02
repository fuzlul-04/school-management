<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->role && in_array($user->role->slug, ['super_admin', 'admin'])) {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->role && $user->role->slug === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }
        
        if ($user->role && $user->role->slug === 'student') {
            return redirect()->route('student.dashboard');
        }
        
        return view('dashboard');
    }

    public function admin()
    {
        $totalStudents = Student::count();
        $totalRevenue = Payment::sum('amount');
        $totalTeachers = Teacher::count();
        $recentPayments = Payment::with(['invoice.student', 'invoice.items.feeType'])->latest()->take(5)->get();

        return view('backend.admin.dashboard', compact('totalStudents', 'totalRevenue', 'totalTeachers', 'recentPayments'));
    }

    public function teacher(Request $request)
    {
        $user = $request->user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        $classes = $teacher 
            ? \App\Models\TeacherClass::where('teacher_id', $teacher->id)
                ->with(['class', 'section', 'subject', 'academicYear'])
                ->get()
            : collect();

        return view('backend.teacher.dashboard', compact('teacher', 'classes'));
    }

    public function student(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)
            ->with(['class', 'section', 'guardians', 'academicYear'])
            ->first();

        $invoices = $student 
            ? $student->invoices()->with(['items.feeType', 'payments'])->orderBy('due_date', 'desc')->get()
            : collect();

        $results = $student 
            ? $student->results()->with('exam')->orderBy('created_at', 'desc')->get()
            : collect();

        return view('backend.student.dashboard', compact('student', 'invoices', 'results'));
    }
}
