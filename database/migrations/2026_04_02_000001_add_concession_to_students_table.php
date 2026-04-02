<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->enum('concession_type', ['percentage', 'fixed'])->nullable()->after('status');
            $table->decimal('concession_amount', 10, 2)->nullable()->after('concession_type');
            $table->string('concession_reason')->nullable()->after('concession_amount');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['concession_type', 'concession_amount', 'concession_reason']);
        });
    }
};
