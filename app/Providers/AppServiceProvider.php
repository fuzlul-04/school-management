<?php

namespace App\Providers;

use App\Models\Student;
use App\Models\StudentPayment;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schedule::call(function () {
            $defaultFee = config('app.monthly_fee', 500);
            $students = Student::where('status', 'active')->get();
            $month = now()->month;
            $year = now()->year;

            foreach ($students as $student) {
                $exists = StudentPayment::where('student_id', $student->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->exists();

                if (! $exists) {
                    StudentPayment::create([
                        'student_id' => $student->id,
                        'month' => $month,
                        'year' => $year,
                        'amount' => $defaultFee,
                        'status' => 'unpaid',
                    ]);
                }
            }
        })->monthlyOn(1, '00:01');
    }
}
