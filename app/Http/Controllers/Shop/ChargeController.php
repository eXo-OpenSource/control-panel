<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Services\Shop\KlarnaService;
use App\Services\Shop\PayPalService;
use Illuminate\Support\Facades\Session;
use App\Services\Shop\PaySafeCardService;

class ChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('shop.charge.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        if (!auth()->user()->premium->hasBillingAdress()) {
            return redirect(route('accounts.edit'));
        }

        return view('shop.charge.create', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->premium->hasBillingAdress()) {
            return redirect(route('accounts.edit'));
        }


        $data = $request->validate([
            'type' => [
               'required',
               Rule::in(['paypal', 'klarna', 'paysafecard'])
            ],
            'amount' => 'required|integer|gte:5'
        ]);


        $pay = new Payment;
        $pay->user_id = auth()->user()->Id;
        $pay->method = $data['type'];
        $pay->amount = request('amount');
        $pay->currency = 'EUR';
        $pay->status = 'Initial';

        switch ($data['type']) {
            case 'paypal':
                $payment = PayPalService::startPayment($data['amount']);
                $pay->payment_id = $payment['payment_id'];
                $pay->exo_dollar = $pay->amount;
                $pay->save();

                Session::put('payment_id', $payment['payment_id']);
                Session::put('payment_provider', $data['type']);
                return redirect($payment['url']);
                break;
            case 'klarna':
                $payment = KlarnaService::startPayment($data['amount']);
                $pay->payment_id = $payment['payment_id'];
                $pay->exo_dollar = $pay->amount;
                $pay->save();

                Session::put('payment_id', $payment['payment_id']);
                Session::put('payment_provider', $data['type']);
                return redirect($payment['url']);
                break;
            case 'paysafecard':
                $payment = PaySafeCardService::initiatePayment($data['amount'], 'EUR', auth()->user()->Id);

                if ($payment){

                } else {
                    abort(500);
                }

                $pay->payment_id = $payment->id;
                $pay->exo_dollar = floor($pay->amount * 0.9);
                $pay->save();

                Session::put('payment_id', $payment->id);
                Session::put('payment_provider', $data['type']);
                return redirect($payment->redirect->auth_url);
                break;
        }

        return abort(404);
    }

    public function status($status) {

        if (Session::has('payment_id') && Session::has('payment_provider')) {
            switch (Session::get('payment_provider')) {
                case 'paypal':
                    PayPalService::executePayment();
                    break;
                case 'klarna':
                    break;
                case 'paysafecard':
                    // PaySafeCardService::capturePayment(session('payment_id'));
                    break;
            }

            $payment = Payment::where('user_id', auth()->user()->Id)->orderBy('created_at', 'desc')->first();
            return view('charge.status', compact('status', 'payment'));
        } else {
            return abort(404);
        }
    }

}
