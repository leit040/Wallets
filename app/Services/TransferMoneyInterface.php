<?php

namespace App\Services;

interface TransferMoneyInterface
{
    public function transferMoney(int $senderId, int $recipientId, int $senderCurrencyId,int $recipientCurrencyId, float $amount):array;


}
