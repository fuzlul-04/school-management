<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VerifyTables extends Command
{
    protected $signature = 'db:verify';
    protected $description = 'Verify database tables and records';

    public function handle(): int
    {
        $tables = [
            'roles', 'permissions', 'role_permission', 'user_permission',
            'academic_years', 'classes', 'sections', 'subjects', 'class_subject', 
            'teacher_subjects', 'teacher_classes', 'teachers', 'students', 
            'guardians', 'student_guardian', 'admissions', 'attendances', 
            'exams', 'marks', 'results', 'fee_types', 'fee_structures', 
            'invoices', 'invoice_items', 'payments', 'transactions', 
            'payment_logs', 'notices', 'notifications', 'audit_logs'
        ];

        $this->info('Database Schema Verification');
        $this->newLine();
        
        $header = ['Table', 'Records', 'Status'];
        $rows = [];
        
        foreach ($tables as $table) {
            try {
                $count = DB::table($table)->count();
                $status = $count > 0 ? 'OK' : 'Empty';
                $rows[] = [$table, $count, $status];
            } catch (\Exception $e) {
                $rows[] = [$table, 'Error', 'Error: ' . $e->getMessage()];
            }
        }

        $this->table($header, $rows);
        
        $existingTables = DB::select('SHOW TABLES');
        $this->info('Total tables: ' . count($existingTables));
        
        return Command::SUCCESS;
    }
}