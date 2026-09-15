<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crypto_wallets', function (Blueprint $table) {
            $table->id();
            $table->enum('currency', ['BTC', 'ETH', 'USDT']);
            $table->enum('network', ['TRC20', 'ERC20', 'BEP20']);
            $table->string('address');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['currency', 'network']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crypto_wallets');
    }
};
