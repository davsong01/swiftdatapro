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
        Schema::create('transaction_status_change_tracks', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id');
            $table->string('previous_status');
            $table->string('current_status');
            $table->string('transaction_id');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_status_change_tracks');
    }
};
