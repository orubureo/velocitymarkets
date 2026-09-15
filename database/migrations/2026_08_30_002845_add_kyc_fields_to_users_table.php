<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('kyc_status', ['none', 'pending', 'approved', 'rejected'])->default('none')->after('referral_bonus_paid');
            $table->string('kyc_document_type')->nullable()->after('kyc_status');
            $table->string('kyc_document_path')->nullable()->after('kyc_document_type');
            $table->string('kyc_selfie_path')->nullable()->after('kyc_document_path');
            $table->timestamp('kyc_submitted_at')->nullable()->after('kyc_selfie_path');
            $table->timestamp('kyc_reviewed_at')->nullable()->after('kyc_submitted_at');
            $table->string('kyc_rejection_reason')->nullable()->after('kyc_reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'kyc_status',
                'kyc_document_type',
                'kyc_document_path',
                'kyc_selfie_path',
                'kyc_submitted_at',
                'kyc_reviewed_at',
                'kyc_rejection_reason',
            ]);
        });
    }
};
