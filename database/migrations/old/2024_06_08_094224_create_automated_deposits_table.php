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
        Schema::create('automated_deposits', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('account_reference')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_code')->nullable();
            $table->integer('payment_gateway_id');
            $table->string('trans_lower_amt')->nullable();
            $table->string('trans_upper_amt')->nullable();
            $table->double('charge')->nullable();
            $table->string('status')->nullable();
            $table->integer('admin_id')->nullable();
            $table->string('purpose')->default('Wallet Funding');
            $table->string('bvn')->nullable();
            $table->string('nin')->nullable();
            $table->text('raw_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automated_deposits');
    }
};
