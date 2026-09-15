<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
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
            ]);
            $table->decimal('amount', 20, 2); // signed: positive = credit, negative = debit
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->nullableMorphs('reference'); // links to Trade, RoiSubscription, etc. later
            $table->text('note')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
