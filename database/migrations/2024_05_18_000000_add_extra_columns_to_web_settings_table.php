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
                $table->string('seo_title');
            });
        }

        if (!Schema::hasColumn('web_settings', 'seo_description')) {
            Schema::table('web_settings', function (Blueprint $table) {
                $table->string('seo_description');
            });
        }

        if (!Schema::hasColumn('web_settings', 'logo')) {
            Schema::table('web_settings', function (Blueprint $table) {
                $table->string('logo');
            });
        }


        // if (!Schema::hasColumn('web_settings', 'favicon')) {
        //     Schema::table('web_settings', function (Blueprint $table) {
        //         $table->string('favicon');
        //     });
        // }

        // if (!Schema::hasColumn('web_settings', 'enable_mode_selector')) {
        //     Schema::table('web_settings', function (Blueprint $table) {
        //         $table->string('enable_mode_selector');
        //     });
        // }

        // if (!Schema::hasColumn('web_settings', 'enable_switcher')) {
        //     Schema::table('web_settings', function (Blueprint $table) {
        //         $table->string('enable_switcher');
        //     });
        // }
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
