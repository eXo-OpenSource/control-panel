<?php

namespace App\Models\Logs;

use App\Models\Company;
use App\Models\Faction;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $primaryKey = 'Id';
    protected $connection = 'mysql_logs';
    public $timestamps = false;

    protected $dates = ['Date'];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function getGroupName()
    {
        if($this->GroupType === 'faction') {
            return 'Fraktion: ' . Faction::find($this->GroupId)->Name;
        } elseif($this->GroupType === 'company') {
            return 'Unternehmen: ' . Company::find($this->GroupId)->Name;
        } elseif($this->GroupType === 'group') {
            $group = Group::find($this->GroupId);

            if($group) {
                return 'Gruppe: ' . $group->Name;
            }
        }

        return 'Unbekannt';
    }
}
