<?php

namespace App\Services\Shop;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\Models\Shop\PremiumUser;
use DarthSoup\Whmcs\Facades\Whmcs;
use Illuminate\Support\Facades\Log;

class WhmcsService
{
    private static function getClient() {
        return new Client([
            'base_uri'  => config('whmcs.apiurl'),
            'timeout'   => config('whmcs.timeout'),
            'headers'   => ['Accept' => 'application/json']
        ]);
    }

    public static function addUser(PremiumUser $user) {
        if ($user->BillingId && $user->BillingId > 0) {
            return false;
        }

        $response = self::getClient()->post('', [
            'form_params' => [
                'action' => 'AddClient',
                'identifier' => config('whmcs.api.identifier'),
                'secret' => config('whmcs.api.secret'),
                'responsetype' => 'json',

                'firstname' => $user->Firstname,
                'lastname' => $user->Lastname,
                'email' => $user->EMail,
                'address1' => $user->Adress,
                'city' => $user->City,
                'postcode' => $user->PLZ,
                'country' => $user->Country,
                'password2' => Str::random(32),
            ]
        ]);

        $client = json_decode($response->getBody(), true);

        if ($client['result'] === 'error') {
            Log::error($client['message'] . ' for userId ' . $user->ID);
            return false;
        }

        if (isset($client["clientid"]) and $client["clientid"] > 0){
            $user->BillingId = $client["clientid"];
            $user->save();
            return true;
        }
        return false;
    }

    public static function updateUser(PremiumUser $user) {
        if (!$user->BillingId || $user->BillingId === 0) {
            return false;
        }

        $response = self::getClient()->post('', [
            'form_params' => [
                'action' => 'UpdateClient',
                'identifier' => config('whmcs.api.identifier'),
                'secret' => config('whmcs.api.secret'),
                'responsetype' => 'json',

                'action' => 'UpdateClient',
                'clientid' => $user->BillingId,
                'firstname' => $user->Firstname,
                'lastname' => $user->Lastname,
                'email' => $user->EMail,
                'address1' => $user->Adress,
                'city' => $user->City,
                'postcode' => $user->PLZ,
                'country' => $user->Country,
            ]
        ]);

        $client = json_decode($response->getBody(), true);

        if ($client['result'] === 'error') {
            Log::error($client['message'] . ' for userId ' . $user->ID);
            return false;
        }

        return true;
    }

    public static function addInvoice(PremiumUser $user, $paymentMethod, $amount) {
        $response = self::getClient()->post('', [
            'form_params' => [
                'action' => 'CreateInvoice',
                'identifier' => config('whmcs.api.identifier'),
                'secret' => config('whmcs.api.secret'),
                'responsetype' => 'json',

                'userid' => $user->BillingId,
                'status' => 'Paid',
                'sendinvoice' => '1',
                'paymentmethod' => $paymentMethod,
                'taxrate' => '20.00',
                'date' => date("Y-m-d"),
                'duedate' => date("Y-m-d"),
                'itemdescription1' => 'eXo-Dollar',
                'itemamount1' => intval($amount),
                'itemtaxed1' => '1',
            ]
        ]);

        $client = json_decode($response->getBody(), true);

        if ($client['result'] === 'error') {
            Log::error($client['message'] . ' for userId ' . $user->ID);
            return false;
        }

        return $client;
    }
}
