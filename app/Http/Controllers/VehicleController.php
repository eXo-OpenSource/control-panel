<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class VehicleController extends Controller
{
    public function index()
    {
        $data = DB::select('SELECT   v.Model,
                                            v.OwnerType,
                                            IF(v.Premium > 0, true, false) AS Premium,
                                            COUNT(v.Id) AS Count
                                        FROM vrp_vehicles v
                                        WHERE v.Deleted IS NULL
                                        GROUP BY v.OwnerType, IF(v.Premium > 0, true, false), v.Model
                                        ORDER BY v.Model');

        $shop = DB::select('SELECT Model FROM vrp_vehicle_shop_veh');

        $vehicles = [];

        foreach(config('constants.vehicleNames') as $vehicleId => $vehicleName)
        {
            $vehicles[$vehicleId] = (object)[
                'Id' => $vehicleId,
                'Name' => $vehicleName,
                'Count' => 0,
                'PlayerOwned' => 0,
                'FactionOwned' => 0,
                'CompanyOwned' => 0,
                'GroupOwned' => 0,
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
            switch($vehicle->OwnerType)
            {
                case 1:
                    $vehicles[$vehicle->Model]->PlayerOwned = $vehicles[$vehicle->Model]->PlayerOwned + $vehicle->Count;
                    break;
                case 2:
                    $vehicles[$vehicle->Model]->FactionOwned = $vehicles[$vehicle->Model]->FactionOwned + $vehicle->Count;
                    break;
                case 3:
                    $vehicles[$vehicle->Model]->CompanyOwned = $vehicles[$vehicle->Model]->CompanyOwned + $vehicle->Count;
                    break;
                case 4:
                    $vehicles[$vehicle->Model]->GroupOwned = $vehicles[$vehicle->Model]->GroupOwned + $vehicle->Count;
                    break;
            }


            if($vehicle->OwnerType === 1 || $vehicle->OwnerType === 4) {
                if($vehicle->Premium) {
                    $vehicles[$vehicle->Model]->PremiumCount = $vehicles[$vehicle->Model]->TradeAbleCount + $vehicle->Count;
                    $vehicles[$vehicle->Model]->Count = $vehicles[$vehicle->Model]->Count + $vehicle->Count;
                } else {
                    $vehicles[$vehicle->Model]->TradeAbleCount = $vehicles[$vehicle->Model]->TradeAbleCount + $vehicle->Count;
                    $vehicles[$vehicle->Model]->Count = $vehicles[$vehicle->Model]->Count + $vehicle->Count;
                }
            }
        }

        usort($vehicles, function($a, $b) {
           return $a->Count < $b->Count ? -1 : 1;
        });

        return view('vehicles.index', compact('vehicles'));
    }
}
