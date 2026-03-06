<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nav_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('href');
            $table->string('icon')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('nav_items')->onDelete('cascade');
            $table->string('section')->default('main'); // 'main' | 'footer'
            $table->boolean('is_external')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('nav_item_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nav_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->unique(['nav_item_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nav_item_role');
        Schema::dropIfExists('nav_items');
    }
};
