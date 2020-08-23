<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Vehicle extends Model
{
    use SoftDeletes;
    const DELETED_AT = 'Deleted';
    protected $primaryKey = 'Id';

    static $shopVehicles = null;

    public function getName()
    {
        return config('constants.vehicleNames')[$this->Model];
    }

    public function getTunings()
    {
        $data = json_decode($this->Tunings, true)[0];
        return $data;
    }

    public function getTuningColor($num)
    {
        $tunings = $this->getTunings();

        if($tunings && isset($tunings['Color' . $num])) {
            return sprintf('#%02x%02x%02x', $tunings['Color' . $num][0], $tunings['Color' . $num][1], $tunings['Color' . $num][2]);
        }
        return '#000000';
    }

    public function isInShop()
    {
        if(!Vehicle::$shopVehicles) {

            $data = DB::select('SELECT   v.Model,
                                            v.OwnerType,
                                            IF(v.Premium > 0, true, false) AS Premium,
                                            COUNT(v.Id) AS Count
                                        FROM vrp_vehicles v
                                        WHERE v.Deleted IS NULL
                                        GROUP BY v.OwnerType, IF(v.Premium > 0, true, false), v.Model
                                        ORDER BY v.Model');

            Vehicle::$shopVehicles = DB::select('SELECT Model FROM vrp_vehicle_shop_veh');
        }


        foreach(Vehicle::$shopVehicles as $shopVehicle) {
            if($shopVehicle->Model === $this->Model) {
                return true;
                break;
            }
        }

        return false;
    }
}
