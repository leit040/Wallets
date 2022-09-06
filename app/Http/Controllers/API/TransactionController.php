<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferMoneyRequest;
use App\Services\TransferMoneyInterface;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private TransferMoneyInterface $transferMoney;

    public function __construct(TransferMoneyInterface $transferMoney)
    {
        $this->transferMoney = $transferMoney;
    }

    public function transferMoney(TransferMoneyRequest $request)
    {
        $senderId = $request->get('senderId');
        $recipientId = $request->get('recipientId');
        $amount = $request->get('amount');
        $senderCurrencyId = $request->get('senderCurrencyId');
        $recipientCurrencyId = $request->get('recipientCurrencyId');
        $result = $this->transferMoney->transferMoney($senderId, $recipientId, $senderCurrencyId,$recipientCurrencyId, $amount);

        return response()->json($result);


    }
}
