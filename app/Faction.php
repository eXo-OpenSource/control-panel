<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function getActivity($chart)
    {
        $members = $this->members->pluck('Id')->toArray();
        return AccountActivity::getActivity($members, $chart);
    }
}
