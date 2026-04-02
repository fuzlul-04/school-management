<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_number', 30)->unique()->index();
            $table->unsignedBigInteger('payment_id')->nullable()->index();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->unsignedBigInteger('invoice_id')->nullable()->index();
            $table->enum('type', ['payment', 'refund', 'adjustment'])->default('payment');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('BDT');
            $table->string('method', 50)->nullable();
            $table->string('gateway', 50)->nullable()->index();
            $table->string('transaction_id', 100)->nullable()->index();
            $table->string('reference_number', 100)->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'reversed'])->default('completed')->index();
            $table->text('description')->nullable();
            $table->json('meta_data')->nullable();
            $table->timestamps();
            $table->index('created_at');
            $table->index(['type', 'status']);

            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('set null');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null');
        });

        Schema::create('payment_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_id')->nullable()->index();
            $table->string('gateway', 50)->index();
            $table->string('transaction_id', 100)->nullable()->index();
            $table->string('status_code', 20)->nullable();
            $table->string('status_message', 255)->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->text('error_message')->nullable();
            $table->enum('is_success', ['yes', 'no'])->default('no')->index();
            $table->timestamps();
            $table->index('created_at');

            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
            $table->index(['gateway', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
        Schema::dropIfExists('transactions');
    }
};