<?php

namespace App\Models\Shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PremiumVehicle extends Model
{
    protected $connection = 'mysql_premium';
    protected $table = "premium_veh";
    protected $primaryKey = 'ID';

    protected $fillable = ['Model', 'abgeholt', 'Preis', 'Timestamp_buy', 'Timestamp_abgeholt', 'UserId', 'Soundvan'];

    public function getName()
    {
        return config('constants.vehicleNames')[$this->Model];
    }

    public function owner() {
        return User::find($this->UserId);
    }
}
