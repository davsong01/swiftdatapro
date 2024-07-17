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
        if (!Schema::hasColumn('payment_gateways', 'reserved_account_payment_charge_type')) {
            Schema::table('payment_gateways', function (Blueprint $table) {
                $table->string('reserved_account_payment_charge_type')->nullable();
            });
        }

        if (!Schema::hasColumn('payment_gateways', 'reserved_account_payment_charge')) {
            Schema::table('payment_gateways', function (Blueprint $table) {
                $table->string('reserved_account_payment_charge')->nullable();
            });
        }

        if (!Schema::hasColumn('payment_gateways', 'merchant_email')) {
            Schema::table('payment_gateways', function (Blueprint $table) {
                $table->string('merchant_email')->nullable();
            });
        }

        if (!Schema::hasColumn('payment_gateways', 'charge')) {
            Schema::table('payment_gateways', function (Blueprint $table) {
                $table->string('charge')->nullable();
            });
        }

        if (!Schema::hasColumn('payment_gateways', 'card_payment_charge')) {
            Schema::table('payment_gateways', function (Blueprint $table) {
                $table->string('card_payment_charge')->nullable();
            });
        }

        if (!Schema::hasColumn('payment_gateways', 'merchant_email')) {
            Schema::table('payment_gateways', function (Blueprint $table) {
                $table->string('merchant_email')->nullable();
            });
        }

        if (!Schema::hasColumn('payment_gateways', 'slug')) {
            Schema::table('payment_gateways', function (Blueprint $table) {
                $table->string('slug')->nullable();
            });
        }

        if (!Schema::hasColumn('payment_gateways', 'contract_id')) {
            Schema::table('payment_gateways', function (Blueprint $table) {
                $table->string('contract_id')->nullable();
            });
        }

        if (!Schema::hasColumn('payment_gateways', 'base_url')) {
            Schema::table('payment_gateways', function (Blueprint $table) {
                $table->string('base_url')->nullable();
            });
        }


        if (!Schema::hasColumn('payment_gateways', 'sandbox_base_url')) {
            Schema::table('payment_gateways', function (Blueprint $table) {
                $table->string('sandbox_base_url')->nullable();
            });
        }

        if (!Schema::hasColumn('payment_gateways', 'live_base_url')) {
            Schema::table('payment_gateways', function (Blueprint $table) {
                $table->string('live_base_url')->nullable();
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
        // Schema::dropIfExists('rolepermissions');
    }
};
