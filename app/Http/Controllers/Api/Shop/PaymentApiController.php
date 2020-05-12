<?php

namespace App\Http\Controllers\Api\Shop;

use App\Models\Shop\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class PaymentApiController extends Controller
{
    function stats(Request $request) {
        if (!$request->has('from', 'to')) {
            $returnData = array(
                'status' => 'error',
                'message' => 'from or to parameter missing!'
            );
            return response($returnData, Response::HTTP_BAD_REQUEST);
        }
        $stats = [];

        $request->from = Carbon::parse($request->from);
        $request->to = Carbon::parse($request->to);

        $stats['paypal'] = Payment::where([
            ['status', 'Success'],
            ['method', 'paypal'],
            ['created_at', '>=', $request->from],
            ['created_at', '<=', $request->to]
            ])->sum('amount');

        $stats['paysafecard'] = Payment::where([
            ['status', 'Success'],
            ['method', 'paysafecard'],
            ['created_at', '>=', $request->from],
            ['created_at', '<=', $request->to]
        ])->sum('amount');

        $stats['sofort'] = Payment::where([
            ['status', 'Success'],
            ['method', 'sofort'],
            ['created_at', '>=', $request->from],
            ['created_at', '<=', $request->to]
        ])->sum('amount');

        return response(collect($stats)->jsonSerialize(), Response::HTTP_OK);
    }

    function status($paymentId) {
        $payment = Payment::where('payment_id', $paymentId)->first();
        return response(collect($payment)->jsonSerialize(), Response::HTTP_OK);
    }
}
