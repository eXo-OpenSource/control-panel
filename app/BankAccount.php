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
        return $this->hasOne(User::class, 'Id', 'OwnerId');
    }

}
