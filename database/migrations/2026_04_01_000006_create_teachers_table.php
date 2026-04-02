<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('employee_id', 30)->unique()->index();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('bangla_name', 191)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('religion', 50)->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->string('phone', 20)->nullable()->index();
            $table->string('emergency_phone', 20)->nullable();
            $table->string('nationality', 50)->default('Bangladeshi');
            $table->string('nid_number', 30)->nullable()->index();
            $table->string('birth_certificate_number', 30)->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('profile_image', 500)->nullable();
            $table->date('join_date');
            $table->string('designation', 100)->nullable();
            $table->string('qualification', 191)->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('currency', 3)->default('BDT');
            $table->enum('status', ['active', 'inactive', 'on_leave', 'terminated'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index('created_at');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};