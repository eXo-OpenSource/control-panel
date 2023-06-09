<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';

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
        $groupLog = GroupLog::where('GroupType', 'faction');

        if($this->Id === 1 || $this->Id === 2 || $this->Id === 3)
        {
            $groupLog->whereIn('GroupId', [1, 2, 3]);
        }
        else
        {
            $groupLog->where('GroupId', $this->Id);
        }

        return $groupLog;
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

    public function money()
    {
        return BankAccountTransaction::query()->where('FromType', 2)->where('FromId', $this->Id)->orWhere('ToType', 2)->where('ToId', $this->Id);
    }

    public function vehicles()
    {
        return $this->newHasMany(Vehicle::where('OwnerType', 2), $this, 'OwnerId', 'Id');
    }

    public function bank()
    {
        return $this->morphOne(BankAccount::class, 'bank', 'OwnerType', 'OwnerId', 'Id');
    }

    public function bankAccount()
    {
        if($this->Id !== 2 && $this->Id !== 3) {
            return $this->bank;
        }

        return BankAccount::query()->where('OwnerType', 2)->where('OwnerId', 1)->first();
    }

    public function getMorphClass()
    {
        return 2;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function getURL()
    {
        return route('factions.show', $this->Id);
    }
}
