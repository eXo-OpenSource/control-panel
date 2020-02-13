<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PlayerHistory extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'player_history';
    protected $dates = ['JoinDate', 'LeaveDate'];

    public function element()
    {
        if ($this->ElementType === 'faction') {
            return $this->hasOne(Faction::class, 'Id', 'ElementId');
        } else if ($this->ElementType === 'company') {
            return $this->hasOne(Company::class, 'Id', 'ElementId');
        }
    }

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function inviter()
    {
        return $this->hasOne(User::class, 'Id', 'InviterId');
    }

    public function uninviter()
    {
        return $this->hasOne(User::class, 'Id', 'UninviterId');
    }

    public function getDuration()
    {
        $leaveDate = $this->LeaveDate ? $this->LeaveDate : Carbon::now();

        return $leaveDate->locale('de')->longAbsoluteDiffForHumans($this->JoinDate);
    }

    public function getInviter()
    {
        if ($this->inviter) {
            return $this->inviter->Name;
        }

        return 'Unbekannt';
    }

    public function getUninviter()
    {
        if ($this->uninviter) {
            return $this->uninviter->Name;
        }

        return 'Unbekannt';
    }
}
