<?php

namespace App;

use App\Group;
use App\Company;
use App\Faction;
use App\Character;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $primaryKey = 'Id';

    public function ownerUser() {
        return $this->hasOne(User::class, 'Id', 'OwnerType' );
    }

    public function owner()
    {
        //  morphTo($name = null, $type = null, $id = null, $ownerKey = null)
        return $this->morphTo('owner', 'OwnerType', 'OwnerId', 'Id'); // , 'OwnerType', 'Id', 'OwnerId');
    }
}
