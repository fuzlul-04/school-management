<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicYearsSeeder extends Seeder
{
    public function run(): void
    {
        $currentYear = date('Y');
        
        $years = [
            [
                'name' => '2025-2026',
                'start_year' => 2025,
                'end_year' => 2026,
                'start_date' => '2025-01-01',
                'end_date' => '2026-12-31',
                'is_current' => 'no',
                'status' => 'inactive',
            ],
            [
                'name' => '2026-2027',
                'start_year' => 2026,
                'end_year' => 2027,
                'start_date' => '2026-01-01',
                'end_date' => '2027-12-31',
                'is_current' => 'yes',
                'status' => 'active',
            ],
        ];

        DB::table('academic_years')->insert($years);
    }
}