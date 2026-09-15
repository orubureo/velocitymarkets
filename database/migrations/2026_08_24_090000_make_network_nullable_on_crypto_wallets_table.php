<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crypto_wallets', function (Blueprint $table) {
            $table->dropUnique(['currency', 'network']);
        });

        Schema::table('crypto_wallets', function (Blueprint $table) {
            $table->enum('network', ['TRC20', 'ERC20', 'BEP20'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('crypto_wallets', function (Blueprint $table) {
            $table->enum('network', ['TRC20', 'ERC20', 'BEP20'])->nullable(false)->change();
        });

        Schema::table('crypto_wallets', function (Blueprint $table) {
            $table->unique(['currency', 'network']);
        });
    }
};
