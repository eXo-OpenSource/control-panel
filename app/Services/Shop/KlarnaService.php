<?php

namespace App\Services\Shop;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Sofort\SofortLib;

class KlarnaService
{
    /**
     * @param int $amount
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public static function startPayment($amount)
    {
        $sofort = new SofortLib\Sofortueberweisung(config('sofort.key'));
        $sofort->setAmount($amount);
        $sofort->setCurrencyCode('EUR');
        $sofort->setReason('eXo-Dollar Aufladung', 'eXo-Reallife');
        $sofort->setUserVariable(auth()->user()->id);
        $sofort->setSuccessUrl(route('charge.status', 'success'), true);
        $sofort->setAbortUrl(route('charge.status', 'failed'));
        $sofort->setNotificationUrl(route('api.shop.payment.notification.klarna'));
        $sofort->sendRequest();


        if ($sofort->isError()) {
            dd($sofort->getError());
        }

        return [
            'payment_id' => $sofort->getTransactionId(),
            'url' => $sofort->getPaymentUrl()
        ];
    }

    public static function executePayment()
    {
        // https://github.com/paypal/PayPal-PHP-SDK/blob/master/sample/payments/ExecutePayment.php
    }
}
