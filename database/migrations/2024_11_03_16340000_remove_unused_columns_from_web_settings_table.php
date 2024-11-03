<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the backup table already exists
        if (!Schema::hasTable('web_settings_backup')) {
            // Create a backup of the 'web_settings' table
            DB::statement('CREATE TABLE web_settings_backup AS SELECT * FROM web_settings');
        }

        Schema::table('web_settings', function (Blueprint $table) {
            // List of columns to be removed
            $columns = [
                'sms_token',
                'sms_sender',
                'sms_password',
                'sms_username',
                'group_link2',
                'group_link',
                'mtn_n',
                'glo_n',
                'airtel_n',
                'mobile_n',
                'vtpass_account',
                'vtpass_sandbox_account',
                'api_mtn',
                'api_glo',
                'api_glocg',
                'api_airtel',
                'api_mobile',
                'api_mobilesme',
                'api_spectranet',
                'api_smile',
                'api_airtelcg',
                'api_mtn_data_card',
                'api_gift',
                'easyaccess_token',
                'smeplug_token',
                'abumpay_token',
                'simserver_token',
                'ogdam_token',
                'mail_hostname',
                'mail_username',
                'paystack_sk',
                'paystack_pk',
                'flutterwave_sk',
                'flutterwave_pk',
                'monnify_sk',
                'monnify_pk',
                'monnify_cc',
                'monnify_mode',
                'api_url',
                'api_mtn2',
                'api_glo2',
                'api_airtel2',
                'api_mobile2',
                'api_mtn3',
                'api_glo3',
                'api_airtel3',
                'api_mobile3',
                'scrolling_mode',
                'notification1',
                'notification1_mode',
                'notification2',
                'notification2_mode',
                'notification3',
                'notification3_mode',
                'm_lock1',
                'mg_lock1',
                'g_lock1',
                'gcg_lock1',
                'a_lock1',
                'ag_lock1',
                'mo_lock1',
                'm_lock2',
                'g_lock2',
                'a_lock2',
                'mo_lock2',
                'm_lock3',
                'g_lock3',
                'a_lock3',
                'mo_lock3',
                'gotv_lock',
                'dstv_lock',
                'star_lock',
                'waec_lock',
                'waec_registration_lock',
                'gce_lock',
                'neco_lock',
                'nabt_lock',
                'abuja_lock',
                'eko_lock',
                'ibadan_lock',
                'ikeja_lock',
                'jos_lock',
                'kano_lock',
                'kaduna_lock',
                'port_lock',
                'benin_lock',
                'enugu_lock',
                'aba_lock',
                'yola_lock',
                'mobilesme_lock',
                'showmax_lock',
                'sms_access',
                'api_smile_vtu',
                'smile_vtu_lock',
                'sms_api',
                'withdraw_access',
                'withdraw_charge',
                'level2_charge',
                'level2_adminch',
                'level3_charge',
                'level3_adminch',
                'level4_charge',
                'level4_adminch',
                'mdata_comm',
                'mcdata_comm',
                'adata_comm',
                'acdata_comm',
                'spectranet_lock',
                'smile_lock',
                'api_waec',
                'api_waec_registration',
                'api_neco',
                'api_nabteb',
                'api_nbais',
                'nbais_lock',
                'naijaresultpins_api_key',
                'modata_comm',
                'nabteb_comm',
                'bills_comm',
                'sms_comm',
                'mtn_guest',
                'glo_guest',
                'airtel_guest',
                'mobile_guest',
                'mtn_data_card_lock'
            ];

            // Remove columns if they exist
            foreach ($columns as $column) {
                if (Schema::hasColumn('web_settings', $column)) {
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
        // Drop the backup table if it exists (optional, to allow re-running the migration)
        // Schema::dropIfExists('web_settings_backup');

        // Restore columns if necessary
        Schema::table('web_settings', function (Blueprint $table) {
            $table->string('sms_token')->nullable();
            $table->string('sms_sender')->nullable();
            $table->string('sms_password')->nullable();
            $table->string('sms_username')->nullable();
            $table->string('group_link2')->nullable();
            $table->string('group_link')->nullable();
        });
    }
};
