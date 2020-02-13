<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
    protected $primaryKey = 'Id';

    public function members()
    {
        return $this->hasMany(Character::class, 'FactionId', 'Id');
    }

    public function membersCount()
    {
        return Character::where('FactionId', $this->Id)->count();
    }

    public function logs()
    {
        return GroupLog::where('GroupType', 'faction')->where('GroupId', $this->Id);
    }

    public function getActivity(Carbon $from, Carbon $to)
    {
        return AccountActivityGroup::getActivity($this->Id, 2, $from, $to);
    }

    public function getColor($alpha = 1)
    {
        $color = config('constants.factionColors')[0];

        if (config('constants.factionColors')[$this->Id]) {
            $color = config('constants.factionColors')[$this->Id];
        }

        return "rgba(".$color[0].", ".$color[1].", ".$color[2].", ".$alpha.")";
    }

    public function vehicles()
    {
        return $this->newHasMany(Vehicle::where('OwnerType', 2), $this, 'OwnerId', 'Id');
    }

    public function bank()
    {
        return $this->morphOne(BankAccount::class, 'bank', 'OwnerType', 'OwnerId', 'Id');
    }

    public function getMorphClass()
    {
        return 2;
    }
}
