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
}
