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
        if (!Schema::hasColumns('final_transactions', ['is_resolved','admin_id','resolved_on','resolved_transaction_id'])) {
            Schema::table('final_transactions', function (Blueprint $table) {
                $table->integer('is_resolved')->default(0);
                $table->integer('admin_id')->nullable();
                $table->timestamp('resolved_on')->nullable();
                $table->string('resolved_transaction_id')->nullable();
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
        Schema::table('final_transactions', function (Blueprint $table) {
        });
    }
};