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
        if (!Schema::hasColumn('web_settings', 'disable_registration')) {
            Schema::table('web_settings', function (Blueprint $table) {
                $table->string('disable_registration')->default('no');
            });
        }

        if (!Schema::hasColumn('web_settings', 'allowed_domain_emails_for_registration')) {
            Schema::table('web_settings', function (Blueprint $table) {
                $table->json('allowed_domain_emails_for_registration')->default(json_encode(['gmail.com', 'yahoo.com']));
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
