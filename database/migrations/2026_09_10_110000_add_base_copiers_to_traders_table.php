<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('traders', function (Blueprint $table) {
            $table->unsignedInteger('base_copiers')->default(0)->after('roi_30d');
        });
    }

    public function down(): void
    {
        Schema::table('traders', function (Blueprint $table) {
            $table->dropColumn('base_copiers');
        });
    }
};
