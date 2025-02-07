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
        if (!Schema::hasColumns('web_settings', ['bvn_verification_provider_id', 'nin_verification_provider_id'])) {
            Schema::table('web_settings', function (Blueprint $table) {
                $table->integer('bvn_verification_provider_id')->nullable()->after('id');
                $table->integer('nin_verification_provider_id')->nullable()->after('bvn_verification_provider_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apis', function (Blueprint $table) {
            if (Schema::hasColumns('web_settings', ['bvn_verification_provider_id', 'nin_verification_provider_id'])) {
                Schema::table('web_settings', function (Blueprint $table) {
                    $table->dropColumn('bvn_verification_provider_id');
                    $table->dropColumn('nin_verification_provider_id');
                });
            }
        });
    }
};
