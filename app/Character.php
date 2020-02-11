<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Character extends Model
{
    protected $table = 'character';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'Id');
    }

    public function bank()
    {
        return $this->morphOne(BankAccount::class, 'bank', 'OwnerType', 'OwnerId', 'Id');
    }

    public function faction()
    {
        return $this->hasOne(Faction::class, 'Id', 'FactionId');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'Id', 'CompanyId');
    }

    public function group()
    {
        return $this->hasOne(Group::class, 'Id', 'GroupId');
    }

    public function vehicles()
    {
        return $this->newHasMany(Vehicle::where('OwnerType', 1), $this, 'OwnerId', 'Id');
    }

    public function history()
    {
        return $this->hasMany(PlayerHistory::class, 'UserId', 'Id');
    }

    public function hardwareStatistic()
    {
        return $this->hasMany(ClientStatistic::class, 'UserId', 'Id');
    }

    public function stats()
    {
        return $this->hasOne(Stats::class, 'Id', 'Id');
    }

    public function getFactionName()
    {
        if ($this->FactionId === 0)
            return 'keine';
        return $this->faction->Name;
    }

    public function getCompanyName()
    {
        if ($this->CompanyId === 0)
            return 'keines';
        return $this->company->Name;
    }

    public function getGroupName()
    {
        if ($this->GroupId === 0 && $this->group)
            return 'keine';
        return $this->group->Name;
    }

    public function hasFaction() {
        return $this->FactionId !== 0;
    }

    public function hasCompany() {
        return $this->CompanyId !== 0;
    }

    public function hasGroup() {
        return $this->GroupId !== 0;
    }

    public function getCollectedCollectableCount()
    {
        $collectables = json_decode($this->Collectables, true);

        if (!$collectables[0]) {
            return 0;
        }
        $collectables = $collectables[0];

        if (!isset($collectables['collected'])) {
            return 0;
        }

        return sizeof($collectables['collected']);
    }

    public function getPlayTime()
    {
        $hours = floor($this->PlayTime / 60);
        $minutes = $this->PlayTime % 60;

        if($minutes < 10) {
            $minutes = '0' . $minutes;
        }

        return $hours . ':' . $minutes;
    }

    public function getWeekActivity()
    {

        $key = 'player:' . $this->Id . ':activity:weekly';

        $sum = Cache::get($key);

        if (!isset($sum)) {
            $date = (new \DateTime('-7 days'))->format('Y-m-d');

            $activities = AccountActivity::query()->where('UserId', $this->Id)->where('Date', '>', $date)->orderBy('Id', 'DESC')->get()->pluck('Duration')->toArray();

            $sum = array_sum($activities);
            Cache::put($key, $sum, 60 * 30);
        }

        return $sum;
    }

    public function getMorphClass()
    {
        return 1;
    }
}
