<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crypto_wallets', function (Blueprint $table) {
            $table->enum('currency', ['BTC', 'ETH', 'USDT', 'SOL'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('crypto_wallets', function (Blueprint $table) {
            $table->enum('currency', ['BTC', 'ETH', 'USDT'])->change();
        });
    }
};
