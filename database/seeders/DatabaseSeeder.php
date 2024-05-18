<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Carbon\Carbon;
use App\Models\Transactions;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class
        ]);
        // Seeder to convert all date in final transactions to timestamp
        // $transactions = Transactions::all();
        // foreach($transactions as $t){
        //     $da = str_replace(" ", "", $t->buyer_date);
        //     $carbonDate = Carbon::createFromFormat('d-M-y h:i A', $da);
            
        //     $t->update(['created_at' => $carbonDate]);
        // }
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
