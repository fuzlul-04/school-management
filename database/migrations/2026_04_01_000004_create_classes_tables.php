<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('academic_year_id')->index();
            $table->string('name', 50);
            $table->integer('numeric_value')->nullable();
            $table->string('section', 10)->nullable();
            $table->string('room_number', 20)->nullable();
            $table->integer('capacity')->default(40);
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->index('created_at');

            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            $table->unique(['academic_year_id', 'name', 'section']);
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('class_id')->index();
            $table->string('name', 20);
            $table->string('room_number', 20)->nullable();
            $table->integer('capacity')->default(40);
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->index('created_at');

            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->unique(['class_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
        Schema::dropIfExists('classes');
    }
};