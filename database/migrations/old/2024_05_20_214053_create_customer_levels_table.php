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
        Schema::create('customer_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order')->unique();
            $table->double('upgrade_amount')->nullable();
            $table->double('admin_inch')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_levels');
    }
};
