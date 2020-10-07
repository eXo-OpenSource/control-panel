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
    static $rares = null;

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

    public function isUnique()
    {

        if(!Vehicle::$rares)
        {
            $data = DB::select('SELECT v.Model,
                                            COUNT(v.Id) AS Count
                                        FROM vrp_vehicles v
                                        WHERE v.Deleted IS NULL AND (v.OwnerType = 1 OR v.OwnerType = 4)
                                        GROUP BY v.Model
                                        ORDER BY v.Model');


            Vehicle::$rares = [];

            foreach($data as $entry)
            {
                Vehicle::$rares[$entry->Model] = $entry->Count;
            }
        }

        if(isset(Vehicle::$rares[$this->Model]) && Vehicle::$rares[$this->Model] <= 1)
        {
            return true;
        }
        return false;
    }

    public function isUltraRare()
    {
        $this->isUnique();

        if(isset(Vehicle::$rares[$this->Model]) && Vehicle::$rares[$this->Model] <= 4)
        {
            return true;
        }
        return false;
    }

    public function getPremiumOwner()
    {
        if($this->Premium > 0 && $this->OwnerType !== 1)
        {
            $user = User::find($this->Premium);

            if($user)
            {
                return $user->Name;
            }
        }

        return false;
    }
}
