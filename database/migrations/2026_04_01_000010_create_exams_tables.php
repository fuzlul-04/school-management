<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('academic_year_id')->index();
            $table->string('name', 100);
            $table->enum('type', ['monthly', 'terminal', 'annual', 'ct', 'final'])->default('monthly');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('is_published', ['yes', 'no'])->default('no')->index();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->index('created_at');
            $table->index(['academic_year_id', 'start_date']);

            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
        });

        Schema::create('marks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('exam_id')->index();
            $table->unsignedBigInteger('subject_id')->index();
            $table->unsignedBigInteger('class_id')->index();
            $table->unsignedBigInteger('academic_year_id')->index();
            $table->unsignedBigInteger('teacher_id')->nullable()->index();
            $table->decimal('written_mark', 5, 2)->nullable();
            $table->decimal('mcq_mark', 5, 2)->nullable();
            $table->decimal('practical_mark', 5, 2)->nullable();
            $table->decimal('ca_mark', 5, 2)->nullable();
            $table->decimal('total_mark', 5, 2)->nullable();
            $table->string('grade', 5)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->index('created_at');

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
            $table->unique(['student_id', 'exam_id', 'subject_id'], 'marks_unique');
            $table->index(['exam_id', 'class_id']);
            $table->index(['student_id', 'academic_year_id']);
        });

        Schema::create('results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('exam_id')->index();
            $table->unsignedBigInteger('academic_year_id')->index();
            $table->decimal('total_mark', 7, 2)->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->string('grade', 2)->nullable();
            $table->integer('rank')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->index('created_at');

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            $table->unique(['student_id', 'exam_id', 'academic_year_id'], 'result_unique');
            $table->index(['exam_id', 'rank']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
        Schema::dropIfExists('marks');
        Schema::dropIfExists('exams');
    }
};