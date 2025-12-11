<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateUserWalletsSeeder extends Seeder
{
    public function run()
    {
        try {
            DB::beginTransaction();

            DB::table('users')->select('id', 'wallets')->orderBy('id')->chunk(500, function ($users) {
                foreach ($users as $user) {
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update([
                            'old_wallets' => $user->wallets,
                            'wallets'     => $user->wallets / 2,
                        ]);
                }
            });
            
            DB::commit();
        } catch (\Exception $e) {
            // Rollback if anything goes wrong
            DB::rollBack();
        }
    }
}
