<?php

namespace App\Services\Shop;


use App\Models\Shop\Payment;
use App\Models\Shop\PremiumUser;
use App\Models\Shop\Transaction;
use DarthSoup\Whmcs\Facades\Whmcs;

class TransactionService
{
    /**
     * @param int $userId
     * @param float $amount
     * @param string $reason
     * @param int|null $paymentId
     */
    public static function addCredit($userId, $amount, $reason, $paymentId = NULL)
    {
        $user = PremiumUser::where('UserId', $userId)->first();

        if ($paymentId !== NULL) {
            if (!$user->BillingId || $user->BillingId === 0) {
                WhmcsService::addUser($user);
            }
            $payment = Payment::find($paymentId)->first();
            WhmcsService::addInvoice($user, $payment->method, $amount);
        }

        $transaction = new Transaction();
        $transaction->user_id = $userId;
        $transaction->amount = $amount;
        $transaction->positive = true;
        $transaction->payment_id = $paymentId;
        $transaction->reason = $reason;
        $transaction->save();

        $user->Miami_Dollar += $amount;
        $user->save();
    }

    public static function takeCredit($userId, $amount, $reason, $paymentId = NULL)
    {
        $transaction = new Transaction();
        $transaction->user_id = $userId;
        $transaction->amount = $amount;
        $transaction->positive = false;
        $transaction->payment_id = $paymentId;
        $transaction->reason = $reason;
        $transaction->save();

        $user = PremiumUser::where('UserId', $userId)->first();
        $user->Miami_Dollar -= $amount;
        $user->save();
    }
}
