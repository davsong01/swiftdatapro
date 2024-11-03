<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasColumn('web_settings', 'kyc_verification_mode')) {
            Schema::table('web_settings', function (Blueprint $table) {
                $table->enum('kyc_verification_mode', ['manual','auto'])->default('manual');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->dropColumn('kyc_verification_mode');
        });
    }
};