<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recorded_classes', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->string('subject');
            $table->string('chapter')->nullable();
            $table->string('title');
            $table->string('video_url');
            $table->string('thumbnail')->nullable();
            $table->date('recorded_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recorded_classes');
    }
};
