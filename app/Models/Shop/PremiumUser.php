<?php

namespace App\Models\Shop;

use Carbon\Carbon;
use App\Models\TeamSpeakIdentity;
use App\Models\Shop\PremiumVehicle;
use Illuminate\Database\Eloquent\Model;

class PremiumUser extends Model
{
    protected $connection = 'mysql_premium';
    protected $table = "user";
    protected $primaryKey = 'UserId';

    public $timestamps = false;
    protected $fillable = ['UserId', 'Name', 'game_id'];

    public function getPremiumDays() {
        $to = Carbon::createFromTimestamp($this->premium_bis);
        $days = Carbon::now()->diffInDays($to);
        return $days >= 0 ? $days : 0;
    }

    public function getPremiumVehicleAmount() {
        return count($this->vehicles()->get());
    }

    public function vehicles()
    {
        return $this->hasMany(PremiumVehicle::class, "UserId", "UserId");
    }

    public function hasBillingAdress() {
        return isset($this->Firstname, $this->Lastname, $this->EMail, $this->Adress, $this->PLZ, $this->City, $this->Country);
    }

    public function teamSpeakIdentities()
    {
        return $this->hasMany(TeamSpeakIdentity::class, 'UserId', 'UserId');
    }
}
