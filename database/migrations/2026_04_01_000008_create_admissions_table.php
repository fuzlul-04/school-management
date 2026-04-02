<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('application_id', 30)->unique()->index();
            $table->unsignedBigInteger('academic_year_id')->index();
            $table->unsignedBigInteger('class_id')->index();
            $table->unsignedBigInteger('section_id')->nullable()->index();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('bangla_name', 191)->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('religion', 50)->nullable();
            $table->string('phone', 20)->index();
            $table->string('email', 191)->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('father_name', 100);
            $table->string('father_phone', 20)->nullable();
            $table->string('father_nid', 30)->nullable();
            $table->string('mother_name', 100);
            $table->string('mother_phone', 20)->nullable();
            $table->string('mother_nid', 30)->nullable();
            $table->string('previous_school', 191)->nullable();
            $table->string('ssc_roll', 30)->nullable();
            $table->integer('ssc_passing_year')->nullable();
            $table->decimal('ssc_gpa', 4, 2)->nullable();
            $table->string('profile_image', 500)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'waiting_list', 'admitted'])->default('pending')->index();
            $table->text('remarks')->nullable();
            $table->date('interview_date')->nullable();
            $table->integer('merit_position')->nullable();
            $table->timestamps();
            $table->index('created_at');

            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};