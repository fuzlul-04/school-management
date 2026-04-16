<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solve_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->string('subject');
            $table->string('exam_type');
            $table->integer('year');
            $table->string('title');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solve_sheets');
    }
};
