<?php


namespace App\Http\Controllers\Api\Shop;


use GuzzleHttp\Client;
use App\Models\Shop\Payment;
use Illuminate\Http\Request;
use GuzzleHttp\RequestOptions;

use Sofort\SofortLib\Notification;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Sofort\SofortLib\TransactionData;
use Illuminate\Support\Facades\Storage;
use App\Services\Shop\PaySafeCardService;
use App\Services\Shop\PaypalService;
use App\Services\Shop\TransactionService;

class PaymentNotificationController extends Controller
{
    function paypal(Request $request)
    {
        PaypalService::handleIPN();

        return response('', 200);
    }

    function paysafecard(Request $request)
    {
        $paymentId = request('mtid');

        if (empty($paymentId)) {
            return response(json_encode(['error' => 'Malformed request']), 400)
                ->header('Content-Type', 'application/json');
        }

        $payment = PaySafeCardService::retrievePayment($paymentId);
        Log::info((array)$payment);

        if ($payment->status === 'AUTHORIZED') {
            $payment = PaySafeCardService::capturePayment($paymentId);
            Log::info((array)$payment);

            $payment = PaySafeCardService::retrievePayment($paymentId);
            Log::info((array)$payment);
            if ($payment->status === 'SUCCESS') {
                $pay = Payment::query()->where('payment_id', $paymentId)->first();
                $pay->status = 'Success';
                $pay->save();

                TransactionService::addCredit($pay->user_id, $pay->exo_dollar, "Aufladen: PaySafeCard", $pay->id);
            }
        }

        return response('', 200);
    }

    function klarna(Request $request)
    {
        $SofortLib_Notification = new Notification();
        $Notification = $SofortLib_Notification->getNotification($request->getContent());

        $SofortLibTransactionData = new TransactionData(config('sofort.key'));
        $SofortLibTransactionData->addTransaction($Notification);
        $SofortLibTransactionData->setApiVersion('2.0');
        $SofortLibTransactionData->sendRequest();

        $paymentId = $SofortLibTransactionData->getTransaction();

        $pay = Payment::query()->where('payment_id', $paymentId)->first();

        if ($pay->status == "Initial" && $SofortLibTransactionData->getAmount() == $pay->exo_dollar) {
            $pay->status = 'Success';
            $pay->save();
            TransactionService::addCredit($pay->user_id, $pay->exo_dollar, "Aufladen: Klarna (Sofortüberweisung)", $pay->id);
        }

        return response('', 200);
    }
}

