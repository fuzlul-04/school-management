<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Result;
use App\Services\ResultService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Mpdf\Mpdf;

class ResultController extends Controller
{
    public function __construct(
        private ResultService $resultService
    ) {}

    public function index(Request $request): View
    {
        $query = Result::with(['student', 'exam', 'academicYear']);

        if ($request->has('exam_id') && $request->exam_id) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->has('student_id') && $request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('grade') && $request->grade) {
            $query->where('grade', $request->grade);
        }

        $results = $query->latest()->paginate(25)->appends($request->query());
        $exams = Exam::where('is_published', 'yes')->with('academicYear')->get();

        return view('backend.results.index', compact('results', 'exams'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
        ]);

        $results = $this->resultService->generateResults($request->exam_id);
        $exam = Exam::find($request->exam_id);

        return back()->with('success', "Results generated for {$exam->name}. Total students: {$results->count()}");
    }

    public function show(Result $result): View
    {
        $result->load(['student', 'student.class', 'student.section', 'exam', 'academicYear']);
        
        $marks = $this->resultService->getStudentMarks($result->student_id, $result->exam_id);

        return view('backend.results.show', compact('result', 'marks'));
    }

    public function exportPDF(Result $result)
    {
        $result->load([
            'student', 
            'student.class', 
            'student.section', 
            'exam', 
            'exam.academicYear',
            'academicYear'
        ]);
        
        $marks = $this->resultService->getStudentMarks($result->student_id, $result->exam_id);

        $schoolInfo = [
            'name' => config('app.name', 'School Management System'),
            'address' => config('app.address', ''),
            'phone' => config('app.phone', ''),
            'email' => config('app.email', ''),
        ];

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'nikosh',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $html = view('backend.results.pdf.report-card', compact('result', 'marks', 'schoolInfo'))->render();
        
        $pdf->WriteHTML($html);

        $fileName = "report-card-{$result->student->student_id}-{$result->exam->name}.pdf";
        
        return $pdf->Output($fileName, 'I');
    }

    public function exportAllResults(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
        ]);

        $exam = Exam::with('academicYear')->findOrFail($request->exam_id);
        $results = $this->resultService->getExamResults($request->exam_id);

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'default_font' => 'nikosh',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $html = view('backend.results.pdf.result-sheet', compact('exam', 'results'))->render();
        
        $pdf->WriteHTML($html);

        $fileName = "results-{$exam->name}.pdf";
        
        return $pdf->Output($fileName, 'I');
    }

    public function studentResult(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'nullable|exists:exams,id',
        ]);

        $studentId = $request->student_id;
        $examId = $request->exam_id;

        if ($examId) {
            $result = Result::where('student_id', $studentId)
                ->where('exam_id', $examId)
                ->first();

            if (!$result) {
                return back()->with('error', 'No result found for this exam.');
            }

            return redirect()->route('results.show', $result);
        }

        $results = Result::where('student_id', $studentId)
            ->with(['exam', 'academicYear'])
            ->latest()
            ->get();

        if ($results->isEmpty()) {
            return back()->with('error', 'No results found for this student.');
        }

        if ($results->count() === 1) {
            return redirect()->route('results.show', $results->first());
        }

        return view('backend.results.student-results', compact('results', 'studentId'));
    }

    public function getRank(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
        ]);

        $results = $this->resultService->getExamResults($request->exam_id);

        return response()->json([
            'results' => $results,
            'total_students' => $results->count(),
        ]);
    }
}
