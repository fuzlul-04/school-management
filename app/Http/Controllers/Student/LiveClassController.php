<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LiveClass;

class LiveClassController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $todayClasses = LiveClass::where('class_name', $student->class?->name)
            ->whereDate('start_time', now()->toDateString())
            ->orderBy('start_time')
            ->get();

        $upcomingClasses = LiveClass::where('class_name', $student->class?->name)
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        foreach ($todayClasses as $class) {
            $class->status = $this->calculateStatus($class);
            $class->save();
        }

        return view('student.live-class', compact('todayClasses', 'upcomingClasses'));
    }

    private function calculateStatus($class)
    {
        $now = now();

        if ($now >= $class->end_time) {
            return 'ended';
        }

        if ($now >= $class->start_time && $now < $class->end_time) {
            return 'live';
        }

        return 'scheduled';
    }
}
