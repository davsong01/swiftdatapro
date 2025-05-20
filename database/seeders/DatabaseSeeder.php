<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Carbon\Carbon;
use App\Models\User;
use App\Models\KycData;
use App\Models\Transactions;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::doesntHave('kycData')
        // ->where(function ($query) {
        //     $query->where(function ($q) {
        //         $q->whereNotNull('bvn')
        //         ->where('bvn', '<>', '');
        //     })
        //         ->orWhere(function ($q) {
        //             $q->whereNotNull('nin')
        //             ->where('nin', '<>', '');
        //         });
        // })
        ->chunkById(100, function ($users) {
            foreach ($users as $user) {
                KycData::updateOrCreate(['user_id' => $user->id], [
                    'user_id' => $user->id,
                    'bvn' => $user->bvn,
                    'nin' => $user->nin,
                    'bvn_data' => $user->bvn_data,
                ]);
                
                // Log user ID to confirm creation
                Log::info("Created KycData for user ID: {$user->id}");
            }
        });

        
        $this->call([
            RoleSeeder::class
        ]);

        // php artisan db:seed --class=RoleSeeder
    }
}
