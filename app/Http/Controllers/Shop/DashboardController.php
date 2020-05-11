<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Models\Shop\PremiumUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->Id;
        $premium = PremiumUser::find($id);
        return view('shop.dashboard', [
            'dollars' => $premium->Miami_Dollar,
            'days' => $premium->getPremiumDays(),
            'vehicle_amount' => $premium->getPremiumVehicleAmount()
        ]);
    }
}
