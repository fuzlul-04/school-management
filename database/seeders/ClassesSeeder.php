<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = DB::table('academic_years')
            ->where('is_current', 'yes')
            ->first();

        if (!$academicYear) {
            $academicYear = DB::table('academic_years')->first();
        }

        if ($academicYear) {
            $classes = [
                ['academic_year_id' => $academicYear->id, 'name' => 'Play', 'numeric_value' => 0, 'capacity' => 30, 'status' => 'active'],
                ['academic_year_id' => $academicYear->id, 'name' => 'Nursery', 'numeric_value' => 0, 'capacity' => 30, 'status' => 'active'],
                ['academic_year_id' => $academicYear->id, 'name' => 'One', 'numeric_value' => 1, 'capacity' => 40, 'status' => 'active'],
                ['academic_year_id' => $academicYear->id, 'name' => 'Two', 'numeric_value' => 2, 'capacity' => 40, 'status' => 'active'],
                ['academic_year_id' => $academicYear->id, 'name' => 'Three', 'numeric_value' => 3, 'capacity' => 45, 'status' => 'active'],
                ['academic_year_id' => $academicYear->id, 'name' => 'Four', 'numeric_value' => 4, 'capacity' => 45, 'status' => 'active'],
                ['academic_year_id' => $academicYear->id, 'name' => 'Five', 'numeric_value' => 5, 'capacity' => 50, 'status' => 'active'],
                ['academic_year_id' => $academicYear->id, 'name' => 'Six', 'numeric_value' => 6, 'capacity' => 50, 'status' => 'active'],
                ['academic_year_id' => $academicYear->id, 'name' => 'Seven', 'numeric_value' => 7, 'capacity' => 50, 'status' => 'active'],
                ['academic_year_id' => $academicYear->id, 'name' => 'Eight', 'numeric_value' => 8, 'capacity' => 45, 'status' => 'active'],
                ['academic_year_id' => $academicYear->id, 'name' => 'Nine', 'numeric_value' => 9, 'capacity' => 45, 'status' => 'active'],
                ['academic_year_id' => $academicYear->id, 'name' => 'Ten', 'numeric_value' => 10, 'capacity' => 40, 'status' => 'active'],
            ];

            foreach ($classes as $class) {
                $classId = DB::table('classes')->insertGetId($class);

                $sections = ['A', 'B'];
                foreach ($sections as $section) {
                    DB::table('sections')->insert([
                        'class_id' => $classId,
                        'name' => $section,
                        'capacity' => $class['capacity'] / count($sections),
                        'status' => 'active',
                    ]);
                }
            }
        }
    }
}