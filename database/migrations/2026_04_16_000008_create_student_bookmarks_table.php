<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('recordable_type');
            $table->unsignedBigInteger('recordable_id');
            $table->timestamps();
            $table->unique(['student_id', 'recordable_type', 'recordable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_bookmarks');
    }
};
