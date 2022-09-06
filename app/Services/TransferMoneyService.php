<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;


class TransferMoneyService implements TransferMoneyInterface
{

    public function transferMoney(int $senderId, int $recipientId, int $senderCurrencyId, int $recipientCurrencyId, float $amount): array
    {
        $fee = ($amount / 100) * 2;
        $sender = User::find($senderId);
        if ($senderId == $recipientId && $senderCurrencyId == $recipientCurrencyId) {
            return [
                'status' => false,
                'message' => 'Transfer of one currency by one user'
            ];
        }
        if (!$this->checkBalance($sender, $senderCurrencyId, $amount + $fee)) {
            return [
                'status' => false,
                'message' => 'Insufficient funds in the account'
            ];
        }

        $recipient = User::find($recipientId);
        $senderWallet = $sender->getWalletInSpecifiedCurrency($senderCurrencyId);
        $rate = $this->getRate($recipientCurrencyId);
        if (!$this->checkWallet($recipient, $recipientCurrencyId)) {

            $recipientWallet = $recipient->getWalletInSpecifiedCurrency(1);
        } else {
            $recipientWallet = $recipient->getWalletInSpecifiedCurrency($recipientCurrencyId);

        }
        if ($this->makeTransaction($senderWallet, $recipientWallet, $amount, $fee)) {
            $this->logTransaction($senderWallet->id, $recipientWallet->id, $senderCurrencyId, $recipientCurrencyId, $amount, $fee);
            return [
                'status' => true,
                'message' => 'Transfer accepted'
            ];
        }
        return [
            'status' => false,
            'message' => 'Something wrong - try again '
        ];


    }


    private function makeTransaction(Wallet $senderWallet, Wallet $recipientWallet, float $amount, float $fee): bool
    {
        $fullAmount = $amount + $fee;
        $amount = $this->getAmount($senderWallet->currency_id, $recipientWallet->currency_id, $amount);

        DB::beginTransaction();
        $senderBalance = DB::table('wallets')->select('amount')->where('id', '=', $senderWallet->id)->value('amount');

        if ($senderBalance >= $fullAmount) {
            DB::table('wallets')->where('id', '=', $senderWallet->id)->update(['amount' => ($senderBalance - $fullAmount)]);
            $recipientBalance = DB::table('wallets')->select('amount')->where('id', '=', $recipientWallet->id)->value('amount');
            DB::table('wallets')->where('id', '=', $recipientWallet->id)->update(['amount' => $recipientBalance + $amount]);
            DB::commit();
            return true;
        }
        DB::rollBack();
        return false;
    }


    private function logTransaction(int $senderId, int $recipientId, int $senderCurrencyId, int $recipientCurrencyId, float $amount, float $fee): void
    {
        DB::table('transaction_logs')->insert(['sender_id' => $senderId, 'recipient_id' => $recipientId, 'sender_currency_id' => $senderCurrencyId, 'recipient_currency_id' => $recipientCurrencyId,
            'amount' => $amount, 'system_fee' => $fee, 'created_at' => DB::raw('CURRENT_TIMESTAMP')]);
    }

    private function checkBalance($user, $currencyId, $amount): bool
    {
        return $user->getBalanceInSpecifiedCurrency($currencyId) >= $amount;
    }

    private function checkWallet($user, $currencyId): bool
    {
        return (bool)$user->getWalletInSpecifiedCurrency($currencyId);
    }

    private function getRate(int $currencyId)
    {
        return DB::table('exchange_rate')->select('rate')->where('currency_id', '=', $currencyId)->value('rate');
    }

    private function getAmount(int $beforeCurrencyId, int $afterCurrencyId, float $amount): float
    {
        if ($beforeCurrencyId == $afterCurrencyId && $beforeCurrencyId == 1) return $amount;
        if ($beforeCurrencyId == 1) return $amount / $this->getRate($afterCurrencyId);
        if ($afterCurrencyId == 1) return $amount * $this->getRate($beforeCurrencyId);

        return $amount * $this->getRate($beforeCurrencyId) / $this->getRate($afterCurrencyId);
    }
}
