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
        if (!Schema::hasColumn('apis', 'meta_data')) {
            Schema::table('apis', function (Blueprint $table) {
                $table->json('meta_data')->nullable()->after('updated_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apis', function (Blueprint $table) {
            if (Schema::hasColumns('apis', 'meta_data')) {
                Schema::table('apis', function (Blueprint $table) {
                    $table->dropColumn('meta_data');
                });
            }
        });
    }
};
