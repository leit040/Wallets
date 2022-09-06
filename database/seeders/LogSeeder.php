<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = User::all();
        $currencies = Currency::all();

        for ($i = 0; $i < 3000; $i++) {
            $sender_id = $users->random()->id;
            $recipient_id = $users->random()->id;
            $sender_currency_id = $currencies->random()->id;
            $recipient_currency_id = $currencies->random()->id;
            $amount = rand(10, 5000) / rand(13, 27);
            $system_fee = $amount / 100 * 2;
            $created_at = fake()->dateTimeBetween($startDate = '-3 years', $endDate = 'now', $timezone = null);
            DB::table('transaction_logs')->insert(['sender_id' => $sender_id, 'recipient_id' => $recipient_id, 'sender_currency_id' => $sender_currency_id, 'recipient_currency_id' => $recipient_currency_id,
                'amount' => $amount, 'system_fee' => $system_fee, 'created_at' => $created_at]);

        }

    }
}
