<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('account_tier_id')->nullable()->after('referral_bonus_paid')->constrained()->nullOnDelete();
            $table->timestamp('account_tier_purchased_at')->nullable()->after('account_tier_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('account_tier_id');
            $table->dropColumn('account_tier_purchased_at');
        });
    }
};
