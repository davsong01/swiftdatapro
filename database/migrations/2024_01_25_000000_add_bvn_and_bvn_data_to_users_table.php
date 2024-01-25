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
        if (!Schema::hasColumn('users', 'bvn')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('bvn');
            });
        }

        if (!Schema::hasColumn('users', 'nin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('nin');
            });
        }

        if (!Schema::hasColumn('users', 'bvn_data')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('bvn_data');
            });
        }

        if (!Schema::hasColumn('users', 'kyc_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('kyc_status')->default(0);
            });
        }
        
        if (!Schema::hasColumn('users', 'nin_data')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('nin_data');
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
        Schema::dropIfExists('users_verify');
    }
};
