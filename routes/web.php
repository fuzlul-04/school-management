<?php

use App\Http\Controllers\Backend\AcademicYearController;
use App\Http\Controllers\Backend\AdmissionController;
use App\Http\Controllers\Backend\AttendanceController;
use App\Http\Controllers\Backend\ClassRoutineController;
use App\Http\Controllers\Backend\ClassroomController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ExamController;
use App\Http\Controllers\Backend\FeeStructureController;
use App\Http\Controllers\Backend\FeeTypeController;
use App\Http\Controllers\Backend\FinanceController;
use App\Http\Controllers\Backend\GuardianController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\PaymentController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\ResultController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\StudentController;
use App\Http\Controllers\Backend\SubjectController;
use App\Http\Controllers\Backend\TeacherClassController;
use App\Http\Controllers\Backend\TeacherController;
use App\Http\Controllers\Backend\TeacherSubjectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/teacher/dashboard', [DashboardController::class, 'teacher'])->name('teacher.dashboard');
    Route::get('/student/dashboard', [DashboardController::class, 'student'])->name('student.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('check.permission:roles.manage')->group(function () {
        Route::resource('roles', RoleController::class)->names('roles');
        Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
        Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');
    });

    Route::middleware('check.permission:users.manage')->group(function () {
        Route::resource('permissions', PermissionController::class)->names('permissions');
    });

    Route::resource('academic-years', AcademicYearController::class)->names('academic-years');
    Route::post('academic-years/{academicYear}/set-active', [AcademicYearController::class, 'setActive'])->name('academic-years.set-active');

    Route::resource('classrooms', ClassroomController::class)->names('classrooms');
    Route::get('classrooms/{classroom}/sections/create', [ClassroomController::class, 'createSection'])->name('classrooms.sections.create');
    Route::post('classrooms/{classroom}/sections', [ClassroomController::class, 'storeSection'])->name('classrooms.sections.store');
    Route::get('classrooms/{classroom}/sections/{section}/edit', [ClassroomController::class, 'editSection'])->name('classrooms.sections.edit');
    Route::put('classrooms/{classroom}/sections/{section}', [ClassroomController::class, 'updateSection'])->name('classrooms.sections.update');
    Route::delete('classrooms/{classroom}/sections/{section}', [ClassroomController::class, 'destroySection'])->name('classrooms.sections.destroy');
    Route::get('classrooms/{classroom}/sections-list', [ClassroomController::class, 'getSections'])->name('classrooms.sections');
    
    Route::resource('students', StudentController::class)->names('students');
    Route::post('students/{student}/upload-image', [StudentController::class, 'uploadImage'])->name('students.upload-image');
    Route::post('students/{student}/assign-guardian', [StudentController::class, 'assignGuardian'])->name('students.assign-guardian');
    Route::delete('students/{student}/remove-guardian/{guardian}', [StudentController::class, 'removeGuardian'])->name('students.remove-guardian');
    Route::post('students/{student}/change-class', [StudentController::class, 'changeClass'])->name('students.change-class');

    Route::resource('guardians', GuardianController::class)->names('guardians');

    Route::get('admissions', [AdmissionController::class, 'index'])->name('admissions.index');
    Route::get('admissions/search', [AdmissionController::class, 'search'])->name('admissions.search');
    Route::get('admissions/create', [AdmissionController::class, 'create'])->name('admissions.create');
    Route::post('admissions', [AdmissionController::class, 'store'])->name('admissions.store');
    Route::get('admissions/{admission}', [AdmissionController::class, 'show'])->name('admissions.show');
    Route::get('admissions/{admission}/edit', [AdmissionController::class, 'edit'])->name('admissions.edit');
    Route::put('admissions/{admission}', [AdmissionController::class, 'update'])->name('admissions.update');
    Route::delete('admissions/{admission}', [AdmissionController::class, 'destroy'])->name('admissions.destroy');
    Route::get('admissions/{student}/show-info', [AdmissionController::class, 'showInfo'])->name('admissions.show-info');
    Route::post('admissions/{student}/enroll', [AdmissionController::class, 'enroll'])->name('admissions.enroll');
    Route::post('admissions/{admission}/convert', [AdmissionController::class, 'convertToStudent'])->name('admissions.convert');
    
    Route::resource('teachers', TeacherController::class)->names('teachers');
    
    Route::resource('subjects', SubjectController::class)->names('subjects');
    Route::post('subjects/assign-class', [SubjectController::class, 'assignClass'])->name('subjects.assign-class');
    Route::delete('subjects/remove-class', [SubjectController::class, 'removeClass'])->name('subjects.remove-class');
    
    Route::resource('teacher-subjects', TeacherSubjectController::class)->names('teacher-subjects');
    Route::resource('teacher-classes', TeacherClassController::class)->names('teacher-classes');
    Route::get('teacher-classes/sections/{classId}', [TeacherClassController::class, 'getSections'])->name('teacher-classes.sections');
    Route::get('teacher-classes/subjects/{classId}', [TeacherClassController::class, 'getSubjects'])->name('teacher-classes.subjects');
    
    Route::get('class-routines/timetable', [ClassRoutineController::class, 'timetable'])->name('class-routines.timetable');
    Route::get('class-routines/sections/{classId}', [ClassRoutineController::class, 'getSections'])->name('class-routines.sections');
    Route::resource('class-routines', ClassRoutineController::class)->names('class-routines');
    
    Route::get('attendances/mark', [AttendanceController::class, 'markAttendance'])->name('attendances.mark');
    Route::post('attendances/mark', [AttendanceController::class, 'storeAttendance'])->name('attendances.store-mark');
    Route::get('attendances/students', [AttendanceController::class, 'getStudentsByClass'])->name('attendances.students');
    
    Route::get('attendances/reports/monthly', [AttendanceController::class, 'monthlyReport'])->name('attendances.reports.monthly');
    Route::get('attendances/reports/absentees', [AttendanceController::class, 'absenteeList'])->name('attendances.reports.absentees');
    Route::get('attendances/reports/export', [AttendanceController::class, 'exportPDF'])->name('attendances.reports.export');
    
    Route::resource('attendances', AttendanceController::class)->names('attendances');
    Route::get('attendances/get-students', [AttendanceController::class, 'getStudents'])->name('attendances.get-students');
    Route::post('attendances/bulk', [AttendanceController::class, 'bulkStore'])->name('attendances.bulk');
    
    Route::resource('exams', ExamController::class)->names('exams');
    Route::get('exams/{exam}/marks-entry', [ExamController::class, 'marksEntry'])->name('exams.marks-entry');
    Route::post('exams/{exam}/marks-entry', [ExamController::class, 'saveMarks'])->name('exams.save-marks');
    Route::post('exams/{exam}/publish', [ExamController::class, 'publish'])->name('exams.publish');
    Route::get('exams/subjects', [ExamController::class, 'getSubjects'])->name('exams.subjects');
    Route::get('exams/students', [ExamController::class, 'getStudents'])->name('exams.students');

    Route::get('results', [ResultController::class, 'index'])->name('results.index');
    Route::post('results/generate', [ResultController::class, 'generate'])->name('results.generate');
    Route::get('results/{result}', [ResultController::class, 'show'])->name('results.show');
    Route::get('results/{result}/pdf', [ResultController::class, 'exportPDF'])->name('results.export-pdf');
    Route::get('results/export/all', [ResultController::class, 'exportAllResults'])->name('results.export-all');
    
    Route::resource('fee-types', FeeTypeController::class)->names('fee-types');
    
    Route::resource('fee-structures', FeeStructureController::class)->names('fee-structures');
    
    Route::get('invoices/generate', [InvoiceController::class, 'generate'])->name('invoices.generate');
    Route::post('invoices/generate-preview', [InvoiceController::class, 'generatePreview'])->name('invoices.generate-preview');
    Route::post('invoices/generate', [InvoiceController::class, 'generateStore'])->name('invoices.generate-store');
    Route::get('invoices/students/{classId}', [InvoiceController::class, 'getStudents'])->name('invoices.students');
    Route::get('invoices/fee-structure', [InvoiceController::class, 'getFeeStructure'])->name('invoices.fee-structure');
    Route::get('invoices/fee-amount/{feeTypeId}/{classId}/{academicYearId}', [InvoiceController::class, 'getFeeAmount'])->name('invoices.fee-amount');
    
    Route::resource('invoices', InvoiceController::class)->names('invoices');
    
    Route::resource('payments', PaymentController::class)->names('payments');
    Route::get('payments/invoice/{invoice}', [PaymentController::class, 'getInvoice'])->name('payments.invoice');
    
    Route::get('finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('finance/due-invoices', [FinanceController::class, 'dueInvoices'])->name('finance.due-invoices');
    Route::get('finance/student-summary', [FinanceController::class, 'studentSummary'])->name('finance.student-summary');
    Route::get('finance/student/{student}', [FinanceController::class, 'studentDetail'])->name('finance.student-detail');
    Route::get('finance/reports', [FinanceController::class, 'reports'])->name('finance.reports');
});

require __DIR__.'/auth.php';
