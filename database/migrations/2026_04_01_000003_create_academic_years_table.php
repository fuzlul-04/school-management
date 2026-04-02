<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->integer('start_year');
            $table->integer('end_year');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('is_current', ['yes', 'no'])->default('no')->index();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->index('created_at');
            $table->unique(['start_year', 'end_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};