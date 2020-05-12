<?php

namespace App\Services\Shop;


use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\ItemList;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use App\Models\Shop\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use PayPal\Auth\OAuthTokenCredential;
use App\Models\Shop\PaypalTransaction;
use Illuminate\Support\Facades\Config;

class PayPalService
{
    private static function getApiContext()
    {
        $paypal_conf = Config::get('paypal');
        $mode = $paypal_conf["settings"]["mode"];
        $api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf["credentials"][$mode]['client_id'],
                $paypal_conf["credentials"][$mode]['secret'])
        );
        $api_context->setConfig($paypal_conf['settings']);

        return $api_context;
    }
    /**
     * @param int $amount
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public static function startPayment($amount)
    {
        $api_context = self::getApiContext();

        $payment = new Payment([
            'intent' => 'sale',
            'payer' => new Payer(['payment_method' => 'paypal']),
            'redirect_urls' => new RedirectUrls([
                'return_url' => route('charge.status', 'success'),
                'cancel_url' => route('charge.status', 'failed')
            ]),
            'transactions' => [new Transaction([
                'amount' => new Amount(['currency' => 'EUR', 'total' => $amount]),
                'item_list' => new ItemList([
                    'items' => [
                        new Item(['name' => 'eXo-Dollar', 'currency' => 'EUR', 'quantity' => $amount, 'price' => 1])
                    ]
                ]),
                'description' => 'fuer exo-reallife.de',
                'notify_url' => route('api.shop.payment.notification.paypal')
            ])]
        ]);

        try {
            $payment->create($api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            if (config('app.debug')) {
            } else {
                return redirect()->route('payment.failed', "paypal")->with("error", 'Ein Fehler ist aufgetreten!');
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        /** add payment ID to session **/
        // Session::put('paypal_payment_id', $payment->getId());
        // f (isset($redirect_url)) {
            /** redirect to paypal **/
        //     return Redirect::away($redirect_url);
        // }
        $pay = new \App\PaypalTransaction;
        $pay->user_id = auth()->user()->Id;
        $pay->amount = $amount;
        $pay->currency = 'EUR';
        $pay->status = 'Initial';
        $pay->payment_id = $payment->getId();
        $pay->save();

        return [
            'payment_id' => $payment->getId(),
            'url' => $redirect_url
        ];
        // return redirect()->route('charge.failed', "paypal")->with("error", 'Ein unbekannter Fehler aufgetreten!');
    }

    public static function executePayment()
    {
        $api_context = self::getApiContext();

        $paymentId = request()->get('paymentId');
        $payerId = request()->get('PayerID');

        $payment = Payment::get($paymentId, $api_context);

        $execution = new PaymentExecution();
        $execution->setPayerId(request()->get('PayerID'));

        $result = $payment->execute($execution, $api_context);

        $pay = Payment::query()->where('payment_id', $paymentId)->first();
        $pay2 = PaypalTransaction::query()->where('payment_id', $paymentId)->first();

        if ($result->getState() == 'approved') {
            $pay->status = 'Approved';
            $pay->save();

            $pay2->status = 'Approved';
            $pay2->transaction_id = $result->getTransactions()[0]->getRelatedResources()[0]->getSale()->getId();
            $pay2->save();
        } else {
            $pay->status = 'Failed';
            $pay->save();

            $pay2->status = 'Failed';
            $pay2->save();
            return redirect()->route('charge.failed', "paypal");
        }
    }

    public static function handleIPN()
    {
        $data = request()->all();

        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
        $keyval = explode ('=', $keyval);

        if (count($keyval) == 2)
            $myPost[$keyval[0]] = urldecode($keyval[1]);
        }

        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        $paypal_conf = Config::get('paypal');

        $mode = $paypal_conf["settings"]["mode"];

        $url = 'https://ipnpb.paypal.com/cgi-bin/webscr';

        if ($mode === 'sandbox') {
            $url = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        $result = $res = curl_exec($ch);
        if (!$result) {
          curl_close($ch);
          return false;
          exit;
        }
        curl_close($ch);

        if ($result === 'VERIFIED') {
            $transaction = PaypalTransaction::query()->where('transaction_id', $data['txn_id'])->first();
            $transaction->status = 'Success';
            $transaction->save();

            $pay = Payment::query()->where('payment_id', $transaction->payment_id)->first();
            $pay->status = 'Success';
            $pay->save();


            TransactionService::addCredit($pay->user_id, $pay->exo_dollar, "Aufladen: PayPal", $pay->id);
            return true;
        }

        return false;
    }
}
