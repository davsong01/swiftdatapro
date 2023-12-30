<?php

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
        try {
            $tables = \DB::select('SHOW TABLES');
            $db_name = \DB::getDatabaseName();

            foreach ($tables as $t) {
                $qur = 'Tables_in_' . $db_name;

                if (!Schema::hasColumn($t->$qur, 'app_version') && Schema::hasTable($t->$qur)) {
                    Schema::table($t->$qur, function (Blueprint $table) {
                        $table->string('app_version', 20)->nullable()->after('id');
                    });
                }

                if (!Schema::hasColumn($t->$qur, 'created_at') && Schema::hasTable($t->$qur)) {
                    Schema::table($t->$qur, function (Blueprint $table) {
                        $table->timestamps();
                    });
                }

            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
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
