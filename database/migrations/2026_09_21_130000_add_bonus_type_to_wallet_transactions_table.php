<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->enum('type', [
                'deposit',
                'withdrawal',
                'trade_profit',
                'trade_loss',
                'referral_bonus',
                'roi_payout',
                'copy_trade_profit',
                'copy_trade_loss',
                'admin_adjustment',
                'investment_purchase',
                'copy_trade_allocation',
                'account_upgrade',
                'signal_purchase',
                'trade_void_refund',
                'bonus',
            ])->change();
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->enum('type', [
                'deposit',
                'withdrawal',
                'trade_profit',
                'trade_loss',
                'referral_bonus',
                'roi_payout',
                'copy_trade_profit',
                'copy_trade_loss',
                'admin_adjustment',
                'investment_purchase',
                'copy_trade_allocation',
                'account_upgrade',
                'signal_purchase',
                'trade_void_refund',
            ])->change();
        });
    }
};
