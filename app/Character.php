<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $table = 'character';
    protected $primaryKey = 'Id';


    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class, 'Id', 'BankAccount');
    }

    public function getCollectedCollectableCount() 
    {
        $collectables = json_decode($this->Collectables, true);

        if (!$collectables[0]) {
            return 0;
        }
        $collectables = $collectables[0];

        if (!$collectables['collected']) {
            return 0;
        }

        return sizeof($collectables['collected']);
    }
}
