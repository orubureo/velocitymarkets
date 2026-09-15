<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar_initials', 4)->nullable();
            $table->string('tagline')->nullable();
            $table->enum('tier', ['verified', 'pro', 'elite'])->default('verified');
            $table->decimal('win_rate', 5, 2)->nullable();
            $table->decimal('roi_30d', 8, 2)->nullable();
            $table->decimal('min_copy_amount', 20, 2)->default(50);
            $table->decimal('max_copy_amount', 20, 2)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traders');
    }
};
