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
        if (!Schema::hasColumns('web_settings', ['download_android_app_link', 'download_ios_app_link'])) {
            Schema::table('web_settings', function (Blueprint $table) {
                $table->string('download_android_app_link')->nullable();
                $table->string('download_ios_app_link')->nullable();
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

    }
};
