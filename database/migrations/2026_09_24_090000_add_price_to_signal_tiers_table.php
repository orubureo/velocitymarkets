<?php

use App\Models\SignalTier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('signal_tiers', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->default(0)->after('percent');
        });

        // Backfill the existing percent-based tiers with flat prices.
        $prices = [25 => 200, 50 => 500, 75 => 1500, 100 => 2000];

        foreach ($prices as $percent => $price) {
            SignalTier::where('percent', $percent)->update(['price' => $price]);
        }
    }

    public function down(): void
    {
        Schema::table('signal_tiers', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
