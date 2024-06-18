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
        if (!Schema::hasColumn('final_transactions', 'provider_id')) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->string('provider_id')->nullable();
            });
        }

        if (!Schema::hasColumn('final_transactions', 'variation_id')) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->string('variation_id')->nullable();
            });
        }

        if (!Schema::hasColumn('final_transactions', 'product_id')) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->string('product_id')->nullable();
            });
        }

        if (!Schema::hasColumn('final_transactions', 'category_id')) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->string('category_id')->nullable();
            });
        }

        if (!Schema::hasColumn('final_transactions', 'variations_name')) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->string('variations_name')->nullable();
            });
        }

        if (!Schema::hasColumn('final_transactions', 'provider_charge')) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->string('provider_charge')->nullable();
            });
        }

        if (!Schema::hasColumn('final_transactions', 'profit')) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->string('profit')->nullable();
            });
        }

        if (!Schema::hasColumn('final_transactions', 'channel')) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->string('channel')->nullable();
            });
        }

        if (!Schema::hasColumn('final_transactions', 'api_status')) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->string('api_status')->nullable();
            });
        }

        if (!Schema::hasColumn('final_transactions', 'customer_status')) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->string('customer_status')->nullable();
            });
        }
        if (!Schema::hasColumn('final_transactions', 'reference_id')) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->string('reference_id')->nullable();
            });
        }

        if (!Schema::hasColumn('final_transactions', 'transaction_id')) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->string('transaction_id')->nullable();
            });
        }

        if (!Schema::hasColumn('final_transactions', 'payment_method')) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->string('payment_method')->nullable();
            });
        }

        if (!Schema::hasColumn('final_transactions', 'wallet_funding_provider')) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->string('wallet_funding_provider')->nullable();
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
