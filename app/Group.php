<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $primaryKey = 'Id';

    public function members()
    {
        return $this->hasMany(Character::class, 'GroupId', 'Id');
    }

    public function membersCount()
    {
        return Character::where('GroupId', $this->Id)->count();
    }

    public function logs()
    {
        return GroupLog::where('GroupType', 'group')->where('GroupId', $this->Id);
    }

    public function getActivity($chart)
    {
        $members = $this->members->pluck('Id')->toArray();
        return AccountActivity::getActivity($members, $chart);
    }
}
