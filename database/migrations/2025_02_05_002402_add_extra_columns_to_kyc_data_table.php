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
        if (!Schema::hasColumn('kyc_data', 'verification_data')) {
            Schema::table('kyc_data', function (Blueprint $table) {
                $table->json('verification_data')->nullable()->after('nin');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kyc_data', function (Blueprint $table) {
            if (Schema::hasColumns('kyc_data', 'verification_data')) {
                Schema::table('kyc_data', function (Blueprint $table) {
                    $table->dropColumn('verification_data');
                });
            }
        });
    }
};
