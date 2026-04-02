<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('title', 191);
            $table->text('content');
            $table->enum('type', ['general', 'academic', 'event', 'holiday', 'emergency'])->default('general');
            $table->string('attachment', 500)->nullable();
            $table->json('visible_to_roles')->nullable();
            $table->json('visible_to_classes')->nullable();
            $table->enum('is_published', ['yes', 'no'])->default('no')->index();
            $table->timestamp('publish_date')->nullable();
            $table->timestamp('expire_date')->nullable();
            $table->integer('view_count')->default(0);
            $table->timestamps();
            $table->index('created_at');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['is_published', 'publish_date']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('type', 50);
            $table->string('title', 191);
            $table->text('message')->nullable();
            $table->string('link', 500)->nullable();
            $table->string('icon', 50)->nullable();
            $table->enum('is_read', ['yes', 'no'])->default('no')->index();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index('created_at');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'is_read']);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('event', 50)->index();
            $table->string('module', 50)->index();
            $table->string('description', 255)->nullable();
            $table->string('model_type', 191)->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index('created_at');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['module', 'created_at']);
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notices');
    }
};