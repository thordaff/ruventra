<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            // login, logout, duplicate_session, event_created, account_suspended, account_unsuspended, etc.
            $table->string('action', 64);
            $table->string('description');
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            // info | warning | error | critical
            $table->enum('severity', ['info', 'warning', 'error', 'critical'])->default('info');
            // label colour shown as badge: green | yellow | red | blue | purple | gray | orange
            $table->string('label_color', 16)->default('green');
            $table->timestamps();

            $table->index(['action', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('severity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
