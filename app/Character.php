<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Character extends Model
{
    protected $table = 'character';
    protected $primaryKey = 'Id';

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'Id');
    }

    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class, 'Id', 'BankAccount');
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
        //return $this->hasMany(Vehicle::class, 'ElementId', 'Id');
    }

    public function history()
    {
        return $this->hasMany(PlayerHistory::class, 'UserId', 'Id');
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
        if ($this->GroupId === 0)
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

        return $hours . ':' . $minutes;
    }


    public function getActivity($chart)
    {
        $currentDate = date("Y-m-d", strtotime("-14 days"));;
        $toDate = date("Y-m-d");

        $dates = [];

        array_push($dates, $currentDate);

        while ($currentDate !== $toDate) {
            $currentDate = date("Y-m-d", strtotime($currentDate) + strtotime("+1 day") - strtotime($toDate));
            array_push($dates, $currentDate);
        }

        $activity = DB::select('SELECT Date, SUM(Duration) AS Duration FROM vrp_accountActivity WHERE UserID = ? AND Date IN (' . join(', ', $dates) . ') GROUP BY Date;', [$this->Id]);

        $activity = (array)$activity;

        foreach ($dates as $date) {
            $found = false;

            foreach ($activity as $act) {
                if(((array)$act)['Date'] === $date) {
                    $found = true;
                }
            }

            if (!$found) {
                array_push($activity, array(
                    "Date" => $date,
                    "Duration" => '0'
                ));
            }
        }

        usort($activity, function($a, $b) {
            return strcmp(((array)$a)['Date'], ((array)$b)['Date']);
        });

        if ($chart) {
            $activity = (array)$activity;
            $chartData = [
                'labels' => [],
                'datasets' => []
            ];

            $dataset = ['label' => 'Aktivität in h', 'data' => []];

            foreach($activity as $act) {
                array_push($chartData['labels'], ((array)$act)['Date']);
                array_push($dataset['data'] , round(((array)$act)['Duration'] / 60, 1));
            }

            array_push($chartData['datasets'], $dataset);

            return $chartData;
        }

        return $activity;
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
}
