<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('approved_at');
        });

        Schema::table('support_tickets', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('responded_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['approved_at']);
        });

        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['responded_at']);
        });
    }
};
