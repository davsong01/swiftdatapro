<?php

use App\Models\User;
use App\Models\KycData;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {        
        if(!Schema::hasTable('kyc_data')){
            Schema::create('kyc_data', function (Blueprint $table) {
                $table->id();
                $table->string('user_id');
                $table->string('bvn')->nullable();
                $table->text('bvn_data')->nullable();
                $table->string('nin')->nullable();
                $table->string('means_of_identification_type')->nullable();
                $table->string('means_of_identification_document')->nullable();
                            
                $table->timestamps();
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
        Schema::dropIfExists('kyc_data');
    }
};