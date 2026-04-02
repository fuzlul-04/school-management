<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesPermissionsSeeder::class,
            AcademicYearsSeeder::class,
            ClassesSeeder::class,
        ]);

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@school.edu',
            'phone' => '+8801700000000',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'status' => 'active',
        ]);

        echo "Admin created: admin@school.edu / password\n";
    }
}