<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnusedColumnsFromUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Check and drop columns if they exist
            $columns = [
                'old_ref_by',
                'old_username',
                'old_reserved_account',
                'block',
                'wema',
                'acctname',
                'rolez',
                'sterling',
                'fidelity',
                'mtnsme_access',
                'mtncg_access',
                'glo_access',
                'airtel_access',
                'airtelcg_access',
                'mobile_access',
                'cable_access',
                'bills_access',
                'epin_access',
                'airtime_access',
                'susbscription'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add columns back if necessary (you can specify their types if needed)
            $table->string('old_ref_by')->nullable();
            $table->string('old_username')->nullable();
            $table->string('old_reserved_account')->nullable();
            $table->boolean('block')->default(false);
            $table->string('wema')->nullable();
            $table->string('acctname')->nullable();
            $table->string('rolez')->nullable();
            $table->string('sterling')->nullable();
            $table->string('fidelity')->nullable();
            $table->boolean('mtnsme_access')->default(false);
            $table->boolean('mtncg_access')->default(false);
            $table->boolean('glo_access')->default(false);
            $table->boolean('airtel_access')->default(false);
            $table->boolean('airtelcg_access')->default(false);
            $table->boolean('mobile_access')->default(false);
            $table->boolean('cable_access')->default(false);
            $table->boolean('bills_access')->default(false);
            $table->boolean('epin_access')->default(false);
            $table->boolean('airtime_access')->default(false);
            $table->boolean('susbscription')->default(false);
        });
    }
}