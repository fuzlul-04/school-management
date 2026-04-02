<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('bangla_name', 191)->nullable();
            $table->enum('type', ['tuition', 'admission', 'exam', 'library', 'transport', 'food', 'uniform', 'other'])->default('tuition');
            $table->text('description')->nullable();
            $table->enum('is_recurring', ['yes', 'no'])->default('yes');
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->index('created_at');
        });

        Schema::create('fee_structures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fee_type_id')->index();
            $table->unsignedBigInteger('class_id')->nullable()->index();
            $table->unsignedBigInteger('academic_year_id')->index();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('BDT');
            $table->enum('is_mandatory', ['yes', 'no'])->default('yes');
            $table->timestamps();
            $table->index('created_at');

            $table->foreign('fee_type_id')->references('id')->on('fee_types')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            $table->unique(['fee_type_id', 'class_id', 'academic_year_id'], 'fee_structure_unique');
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number', 30)->unique()->index();
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('academic_year_id')->index();
            $table->unsignedBigInteger('class_id')->index();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);
            $table->string('currency', 3)->default('BDT');
            $table->enum('status', ['draft', 'issued', 'partial', 'paid', 'cancelled'])->default('draft')->index();
            $table->date('issue_date');
            $table->date('due_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('created_at');

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->index(['student_id', 'status']);
            $table->index(['status', 'due_date']);
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id')->index();
            $table->unsignedBigInteger('fee_type_id')->index();
            $table->string('description', 191)->nullable();
            $table->decimal('quantity', 5, 2)->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('currency', 3)->default('BDT');
            $table->timestamps();
            $table->index('created_at');

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('fee_type_id')->references('id')->on('fee_types')->onDelete('cascade');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id')->index();
            $table->string('payment_number', 30)->unique()->index();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('BDT');
            $table->enum('method', ['cash', 'bkash', 'nagad', 'rocket', 'bank', 'card', 'other'])->nullable();
            $table->string('gateway')->nullable()->index();
            $table->string('transaction_id', 100)->nullable()->index();
            $table->json('payload')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending')->index();
            $table->timestamp('payment_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('created_at');

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->index(['invoice_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('fee_structures');
        Schema::dropIfExists('fee_types');
    }
};