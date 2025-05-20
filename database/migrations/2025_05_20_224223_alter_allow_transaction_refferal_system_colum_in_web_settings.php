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
        if(Schema::hasColumn('web_settings', 'refferal_commission_settings')) {
            return;
        }
        
        Schema::table('web_settings', function (Blueprint $table) {
            $table->renameColumn('allow_transaction_refferal_system', 'refferal_commission_settings');
        });

        Schema::table('web_settings', function (Blueprint $table) {
            $table->json('refferal_commission_settings')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->string('refferal_commission_settings')->change(); // Adjust based on original type
        });

        Schema::table('web_settings', function (Blueprint $table) {
            $table->renameColumn('refferal_commission_settings', 'allow_transaction_refferal_system');
        });
    }
};
