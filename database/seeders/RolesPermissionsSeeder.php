<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Full system access', 'priority' => 1],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'School administration', 'priority' => 2],
            ['name' => 'Teacher', 'slug' => 'teacher', 'description' => 'Teaching staff', 'priority' => 3],
            ['name' => 'Student', 'slug' => 'student', 'description' => 'Students', 'priority' => 4],
            ['name' => 'Guardian', 'slug' => 'guardian', 'description' => 'Parent/Guardian', 'priority' => 5],
        ];

        DB::table('roles')->insert($roles);

        $permissions = [
            ['name' => 'Dashboard Access', 'slug' => 'dashboard', 'module' => 'System'],
            ['name' => 'User Management', 'slug' => 'users.manage', 'module' => 'Users'],
            ['name' => 'Role Management', 'slug' => 'roles.manage', 'module' => 'Users'],
            ['name' => 'Student Management', 'slug' => 'students.manage', 'module' => 'Students'],
            ['name' => 'Teacher Management', 'slug' => 'teachers.manage', 'module' => 'Teachers'],
            ['name' => 'Attendance Management', 'slug' => 'attendance.manage', 'module' => 'Attendance'],
            ['name' => 'Exam Management', 'slug' => 'exams.manage', 'module' => 'Exams'],
            ['name' => 'Marks Entry', 'slug' => 'marks.entry', 'module' => 'Exams'],
            ['name' => 'Finance Management', 'slug' => 'finance.manage', 'module' => 'Finance'],
            ['name' => 'Notice Management', 'slug' => 'notices.manage', 'module' => 'Notices'],
            ['name' => 'Report Generation', 'slug' => 'reports.generate', 'module' => 'Reports'],
            ['name' => 'Settings', 'slug' => 'settings.manage', 'module' => 'System'],
        ];

        DB::table('permissions')->insert($permissions);

        $rolePermissions = [
            1 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            2 => [1, 4, 5, 6, 7, 8, 9, 10, 11],
            3 => [1, 6, 7, 8],
            4 => [1],
            5 => [1],
        ];

        foreach ($rolePermissions as $roleId => $perms) {
            foreach ($perms as $permId) {
                DB::table('role_permission')->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permId,
                ]);
            }
        }
    }
}