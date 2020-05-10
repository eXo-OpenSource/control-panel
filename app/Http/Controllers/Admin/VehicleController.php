<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class VehicleController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $data = DB::select('SELECT   v.Model,
                                            IF(v.Premium > 0, true, false) AS Premium,
                                            COUNT(v.Id) AS Count
                                        FROM vrp_vehicles v
                                        WHERE (v.OwnerType = 1 OR v.OwnerType = 4) AND v.Deleted IS NULL
                                        GROUP BY IF(v.Premium > 0, true, false), v.Model
                                        ORDER BY v.Model');

        $shop = DB::select('SELECT Model FROM vrp_vehicle_shop_veh');

        $vehicles = [];

        foreach(config('constants.vehicleNames') as $vehicleId => $vehicleName)
        {
            $vehicles[$vehicleId] = (object)[
                'Id' => $vehicleId,
                'Name' => $vehicleName,
                'Count' => 0,
                'TradeAbleCount' => 0,
                'PremiumCount' => 0,
                'IsInShop' => false
            ];

            foreach($shop as $shopVehicle) {
                if($shopVehicle->Model === $vehicleId) {
                    $vehicles[$vehicleId]->IsInShop = true;
                    break;
                }
            }
        }


        foreach($data as $vehicle)
        {
            if($vehicle->Premium) {
                $vehicles[$vehicle->Model]->PremiumCount = $vehicle->Count;
                $vehicles[$vehicle->Model]->Count = $vehicles[$vehicle->Model]->Count + $vehicle->Count;
            } else {
                $vehicles[$vehicle->Model]->TradeAbleCount = $vehicle->Count;
                $vehicles[$vehicle->Model]->Count = $vehicles[$vehicle->Model]->Count + $vehicle->Count;
            }
        }

        usort($vehicles, function($a, $b) {
           return $a->Count < $b->Count;
        });

        return view('admin.vehicles.index', compact('vehicles'));
    }
}
