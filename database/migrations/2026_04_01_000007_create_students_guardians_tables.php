<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('bangla_name', 191)->nullable();
            $table->string('relation', 50)->nullable();
            $table->string('phone', 20)->nullable()->index();
            $table->string('alternative_phone', 20)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('nid_number', 30)->nullable()->index();
            $table->text('profession')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_image', 500)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index('created_at');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('academic_year_id')->index();
            $table->unsignedBigInteger('class_id')->index();
            $table->unsignedBigInteger('section_id')->nullable()->index();
            $table->string('student_id', 30)->unique()->index();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('bangla_name', 191)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('birth_certificate_number', 30)->nullable()->index();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('religion', 50)->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->string('phone', 20)->nullable()->index();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('profile_image', 500)->nullable();
            $table->date('admission_date');
            $table->string('previous_school', 191)->nullable();
            $table->string('ssc_roll', 30)->nullable()->index();
            $table->string('ssc_registration', 30)->nullable();
            $table->integer('ssc_passing_year')->nullable();
            $table->decimal('ssc_gpa', 4, 2)->nullable();
            $table->enum('status', ['active', 'inactive', 'transferred', 'graduated'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index('created_at');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null');
        });

        Schema::create('student_guardian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('guardian_id')->index();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('guardian_id')->references('id')->on('guardians')->onDelete('cascade');
            $table->unique(['student_id', 'guardian_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_guardian');
        Schema::dropIfExists('students');
        Schema::dropIfExists('guardians');
    }
};