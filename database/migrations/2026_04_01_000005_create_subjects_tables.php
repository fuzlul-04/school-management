<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('bangla_name', 191)->nullable();
            $table->string('code', 20)->nullable()->unique()->index();
            $table->enum('type', ['theory', 'practical', 'both'])->default('theory');
            $table->decimal('full_mark', 5, 2)->default(100);
            $table->decimal('pass_mark', 5, 2)->default(33);
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->index('created_at');
        });

        Schema::create('class_subject', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('class_id')->index();
            $table->unsignedBigInteger('subject_id')->index();
            $table->unsignedBigInteger('academic_year_id')->index();
            $table->integer('credit_hour')->default(1);
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->index('created_at');

            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            $table->unique(['class_id', 'subject_id', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_subject');
        Schema::dropIfExists('subjects');
    }
};