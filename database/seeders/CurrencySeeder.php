<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencyData = [
            'Ukrainian  Hryvna' => 'UAH',
            'US Dollar' => 'USD',
            'European Union Euro' => 'EUR'
        ];

        foreach ($currencyData as $key => $value) {
            $currency = Currency::create(['name' => $key, 'code' => $value]);
        }
        DB::table('exchange_rate')->insert([
            ['currency_id' => 1, 'rate' => 1],
            ['currency_id' => 2, 'rate' => 40.3],
            ['currency_id' => 3, 'rate' => 40.5],
        ]);

    }
}
