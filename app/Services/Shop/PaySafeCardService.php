<?php

namespace App\Services\Shop;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class PaySafeCardService
{

    public static function initiatePayment($amount, $currency, $customerId, $minAge = null, $kycLevel = null,
                                    $countryRestriction = null, $submerchantId = null, $shopId = null, $correlationId = null)
    {
        $data = [
            'type' => 'PAYSAFECARD',
            'amount' => $amount,
            'currency' => $currency,
            'redirect' => [
                'success_url' => route('charge.status', 'success'),
                'failure_url' => route('charge.status', 'failed')
            ],
            'notification_url' => route('api.shop.payment.notification.paysafecard'),
            'customer' => [
                'id' => $customerId
            ],

        ];

        if ($countryRestriction != null) {
            $data['customer']['country_restriction'] = $countryRestriction;
        }

        if ($kycLevel != null) {
            $data['customer']['kyc_level'] = $kycLevel;
        }

        if ($minAge != null) {
            $data['customer']['min_age'] = $minAge;
        }

        if ($submerchantId != null) {
            $data['submerchant_id'] = $submerchantId;
        }

        if ($shopId != null) {
            $data['shop_id'] = $shopId;
        }

        $headers = [
            'Authorization' => 'Basic ' . base64_encode(config('paysafecard.key', '')),
            'Content-Type' => 'application/json'
        ];

        if ($correlationId != null) {
            $headers['Correlation-ID'] = $correlationId;
        }

        $client = new Client();

        try {
            $result = $client->post(self::getUrl(), [
                'headers' => $headers,
                'body' => json_encode($data)
            ]);

            $response = \GuzzleHttp\json_decode($result->getBody()->getContents());

            return $response;
        } catch (GuzzleException $exception) {
            Log::info($exception);
            return false;
        }
    }

    public static function retrievePayment($paymentId)
    {
        $headers = [
            'Authorization' => 'Basic ' . base64_encode(config('paysafecard.key', '')),
            'Content-Type' => 'application/json'
        ];

        $client = new Client();

        try {
            $result = $client->get(self::getUrl() . $paymentId, [
                'headers' => $headers
            ]);

            $response = \GuzzleHttp\json_decode($result->getBody()->getContents());

            return $response;
        } catch (GuzzleException $exception) {
            Log::info($exception);
            return false;
        }
    }

    public static function capturePayment($paymentId)
    {
        $headers = [
            'Authorization' => 'Basic ' . base64_encode(config('paysafecard.key', '')),
            'Content-Type' => 'application/json'
        ];

        $client = new Client();

        try {
            $result = $client->post(self::getUrl() . $paymentId . '/capture', [
                'headers' => $headers,
                'body' => json_encode(['id' => $paymentId])
            ]);

            $response = \GuzzleHttp\json_decode($result->getBody()->getContents());

            return $response;
        } catch (GuzzleException $exception) {
            Log::info($exception);
            return false;
        }
    }

    private static function getUrl()
    {
        if (config('paysafecard.env', 'testing') === 'production') {
            return 'https://api.paysafecard.com/v1/payments/';
        } else {
            return 'https://apitest.paysafecard.com/v1/payments/';
        }
    }
}
