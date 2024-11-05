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
        if (!Schema::hasColumn('web_settings', 'seo_title')) {
            Schema::table('web_settings', function (Blueprint $table) {
                $table->string('seo_title')->nullable();
            });
        }

        if (!Schema::hasColumn('web_settings', 'seo_description')) {
            Schema::table('web_settings', function (Blueprint $table) {
                $table->string('seo_description')->nullable();
            });
        }

        if (!Schema::hasColumn('web_settings', 'logo')) {
            Schema::table('web_settings', function (Blueprint $table) {
                $table->string('logo');
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
