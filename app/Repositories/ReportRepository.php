<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ReportRepository
{

    public function generateReport(string $dateFrom, string $dateTo): \Illuminate\Support\Collection
    {
        $result = DB::table('transaction_logs')->selectRaw('sum(amount) as amount,sender_currency_id')
            ->whereBetween('created_at', [$dateFrom, $dateTo])->groupBy('sender_currency_id')->get();
        return $result;
    }

}
