<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Currency;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CurrencySeeder::class
        ]);
        $currecies = Currency::all();
        \App\Models\User::factory(10)->create()->each(function ($user) use ($currecies) {
            foreach ($currecies as $currecy) {
                $amount = rand(100, 10000) / rand(3, 9);
                Wallet::create(['user_id' => $user->id, 'currency_id' => $currecy->id, 'amount' => $amount]);
            }
        });
        $this->call([
            LogSeeder::class
        ]);
    }
}
