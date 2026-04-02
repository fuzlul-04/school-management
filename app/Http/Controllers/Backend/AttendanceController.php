<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Classe;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mpdf\Mpdf;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['student', 'class']);

        if ($request->date) {
            $query->where('date', $request->date);
        }

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->section_id) {
            $query->where('section_id', $request->section_id);
        }

        $attendances = $query->latest()->paginate(25);
        $classrooms = Classe::with('academicYear')->get();
        $sections = Section::all();

        return view('backend.attendances.index', compact('attendances', 'classrooms', 'sections'));
    }

    public function create()
    {
        $classrooms = Classe::with('academicYear')->get();
        return view('backend.attendances.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
        ]);

        $academicYear = AcademicYear::where('is_current', 1)->first();

        foreach ($request->attendances as $data) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'subject_id' => $request->subject_id,
                    'date' => $request->date,
                ],
                [
                    'status' => $data['status'],
                    'class_id' => $request->class_id,
                    'section_id' => $request->section_id ?? null,
                    'academic_year_id' => $academicYear?->id,
                    'teacher_id' => auth()->user()->teacher?->id,
                    'notes' => $data['notes'] ?? null,
                ]
            );
        }

        return redirect()->route('attendances.index')->with('success', 'Attendance marked successfully.');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load(['student', 'subject', 'class']);
        return view('backend.attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $attendance->load(['student', 'subject', 'class']);
        return view('backend.attendances.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($request->all());

        return redirect()->route('attendances.index')->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendances.index')->with('success', 'Attendance deleted successfully.');
    }

    public function getStudents(Request $request)
    {
        $query = Student::where('status', 'active');

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->section_id) {
            $query->where('section_id', $request->section_id);
        }

        $students = $query->orderBy('first_name')->get();

        return response()->json($students);
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
        ]);

        $students = Student::where('class_id', $request->class_id)
            ->where('status', 'active')
            ->get();

        $academicYear = AcademicYear::where('is_current', 1)->first();

        foreach ($students as $student) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'subject_id' => $request->subject_id,
                    'date' => $request->date,
                ],
                [
                    'status' => $request->status,
                    'class_id' => $request->class_id,
                    'section_id' => $request->section_id ?? $student->section_id,
                    'academic_year_id' => $academicYear?->id,
                    'teacher_id' => auth()->user()->teacher?->id,
                    'notes' => $request->notes ?? null,
                ]
            );
        }

        return redirect()->route('attendances.index')->with('success', 'Bulk attendance marked successfully.');
    }

    public function markAttendance()
    {
        $classrooms = Classe::with('academicYear')->get();
        $sections = Section::all();
        
        return view('backend.attendances.mark', compact('classrooms', 'sections'));
    }

    public function storeAttendance(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
        ]);

        $academicYear = AcademicYear::where('is_current', 1)->first();

        foreach ($request->attendances as $data) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'date' => $request->date,
                ],
                [
                    'status' => $data['status'],
                    'class_id' => $request->class_id,
                    'section_id' => $request->section_id,
                    'academic_year_id' => $academicYear?->id,
                    'teacher_id' => auth()->user()->teacher?->id,
                    'notes' => $data['notes'] ?? null,
                    'subject_id' => null,
                ]
            );
        }

        return redirect()->route('attendances.index')->with('success', 'Attendance marked successfully.');
    }

    public function getStudentsByClass(Request $request)
    {
        $query = Student::where('status', 'active');

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->section_id) {
            $query->where('section_id', $request->section_id);
        }

        $students = $query->with('section')->orderBy('first_name')->get();

        $existingAttendance = Attendance::where('date', $request->date ?? date('Y-m-d'))
            ->where('class_id', $request->class_id)
            ->get()
            ->keyBy('student_id');

        $students = $students->map(function ($student) use ($existingAttendance) {
            $attendance = $existingAttendance->get($student->id);
            $student->attendance_status = $attendance?->status ?? null;
            return $student;
        });

        return response()->json($students);
    }

    public function monthlyReport(Request $request)
    {
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');
        $classId = $request->class_id;
        $sectionId = $request->section_id;

        $classrooms = Classe::with('academicYear')->get();
        $sections = Section::all();

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $studentQuery = Student::where('status', 'active')
            ->with(['class', 'section']);

        if ($classId) {
            $studentQuery->where('class_id', $classId);
        }

        if ($sectionId) {
            $studentQuery->where('section_id', $sectionId);
        }

        $students = $studentQuery->orderBy('class_id')->orderBy('first_name')->get();

        $attendanceData = Attendance::whereBetween('date', [$startDate, $endDate])
            ->when($classId, fn($q) => $q->where('class_id', $classId))
            ->get()
            ->groupBy('student_id');

        $students = $students->map(function ($student) use ($attendanceData, $startDate, $endDate) {
            $studentAttendances = $attendanceData->get($student->id, collect());
            $totalDays = $studentAttendances->count();
            $presentDays = $studentAttendances->whereIn('status', ['present', 'late'])->count();
            $absentDays = $studentAttendances->where('status', 'absent')->count();
            $excusedDays = $studentAttendances->where('status', 'excused')->count();

            $student->total_days = $totalDays;
            $student->present_days = $presentDays;
            $student->absent_days = $absentDays;
            $student->excused_days = $excusedDays;
            $student->attendance_percentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

            return $student;
        });

        return view('backend.attendances.reports.monthly', compact(
            'students', 'classrooms', 'sections', 'year', 'month', 'classId', 'sectionId'
        ));
    }

    public function absenteeList(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->toDateString();
        $classId = $request->class_id;
        $sectionId = $request->section_id;

        $classrooms = Classe::with('academicYear')->get();
        $sections = Section::all();

        $query = Attendance::with(['student', 'student.class', 'student.section'])
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'absent');

        if ($classId) {
            $query->where('class_id', $classId);
        }

        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }

        $absentees = $query->orderBy('date')->orderBy('student_id')->get();

        $groupedAbsentees = $absentees->groupBy('student_id')->map(function ($records, $studentId) {
            $student = $records->first()->student;
            return (object) [
                'student' => $student,
                'absent_dates' => $records->pluck('date')->map(fn($d) => $d->format('d M Y'))->toArray(),
                'total_absent' => $records->count(),
            ];
        })->sortBy('student.first_name')->values();

        return view('backend.attendances.reports.absentees', compact(
            'groupedAbsentees', 'classrooms', 'sections', 'startDate', 'endDate', 'classId', 'sectionId'
        ));
    }

    public function exportPDF(Request $request)
    {
        $type = $request->type;

        if ($type === 'monthly') {
            return $this->exportMonthlyReportPDF($request);
        } elseif ($type === 'absentees') {
            return $this->exportAbsenteesPDF($request);
        }

        return redirect()->back()->with('error', 'Invalid report type');
    }

    private function exportMonthlyReportPDF(Request $request)
    {
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');
        $classId = $request->class_id;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $studentQuery = Student::where('status', 'active')
            ->with(['class', 'section']);

        if ($classId) {
            $studentQuery->where('class_id', $classId);
        }

        $students = $studentQuery->orderBy('class_id')->orderBy('first_name')->get();

        $attendanceData = Attendance::whereBetween('date', [$startDate, $endDate])
            ->when($classId, fn($q) => $q->where('class_id', $classId))
            ->get()
            ->groupBy('student_id');

        $students = $students->map(function ($student) use ($attendanceData, $startDate, $endDate) {
            $studentAttendances = $attendanceData->get($student->id, collect());
            $totalDays = $studentAttendances->count();
            $presentDays = $studentAttendances->whereIn('status', ['present', 'late'])->count();
            $absentDays = $studentAttendances->where('status', 'absent')->count();

            $student->total_days = $totalDays;
            $student->present_days = $presentDays;
            $student->absent_days = $absentDays;
            $student->attendance_percentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

            return $student;
        });

        $monthName = Carbon::createFromDate($year, $month, 1)->format('F Y');

        $html = view('backend.attendances.reports.pdf.monthly', [
            'students' => $students,
            'monthName' => $monthName,
            'year' => $year,
            'month' => $month,
        ])->render();

        $mpdf = new Mpdf([
            'default_font' => 'sans-serif',
            'mode' => 'utf-8',
            'format' => 'A4-L',
        ]);

        $mpdf->WriteHTML($html);

        $filename = "attendance-report-{$monthName}.pdf";

        return response()->download($mpdf->Output($filename, 'I'))->header('Content-Type', 'application/pdf');
    }

    private function exportAbsenteesPDF(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->toDateString();
        $classId = $request->class_id;

        $query = Attendance::with(['student', 'student.class', 'student.section'])
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'absent');

        if ($classId) {
            $query->where('class_id', $classId);
        }

        $absentees = $query->orderBy('date')->orderBy('student_id')->get();

        $groupedAbsentees = $absentees->groupBy('student_id')->map(function ($records, $studentId) {
            $student = $records->first()->student;
            return (object) [
                'student' => $student,
                'absent_dates' => $records->pluck('date')->map(fn($d) => $d->format('d M Y'))->toArray(),
                'total_absent' => $records->count(),
            ];
        })->sortBy('student.first_name')->values();

        $html = view('backend.attendances.reports.pdf.absentees', [
            'groupedAbsentees' => $groupedAbsentees,
            'startDate' => Carbon::parse($startDate)->format('d M Y'),
            'endDate' => Carbon::parse($endDate)->format('d M Y'),
        ])->render();

        $mpdf = new Mpdf([
            'default_font' => 'sans-serif',
            'mode' => 'utf-8',
            'format' => 'A4',
        ]);

        $mpdf->WriteHTML($html);

        $filename = "absentee-list-{$startDate}-to-{$endDate}.pdf";

        return response()->download($mpdf->Output($filename, 'I'))->header('Content-Type', 'application/pdf');
    }
}
