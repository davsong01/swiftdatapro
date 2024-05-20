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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->string('status')->default(0);
            $table->string('name')->nullable();
            $table->double('system_price')->nullable();
            $table->string('slug')->unique();
            $table->string('min')->nullable();
            $table->string('max')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->string('display_name')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('has_variations')->default('no');
            $table->string('api_id');
            $table->string('fixed_price')->nullable();
            $table->double('commission')->nullable();
            $table->string('allow_quantity')->default('no');
            $table->string('allow_subscription_type')->default('no');
            $table->string('quantity_graduation')->nullable();
            $table->double('referral_percentage')->nullable();
            
            $table->string('allow_meter_validation')->default('no');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
