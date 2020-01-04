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

    public function vehicles()
    {
        return $this->newHasMany(Vehicle::where('OwnerType', 4), $this, 'OwnerId', 'Id');
    }

    public function bankAccount()
    {
        return $this->newHasOne(BankAccount::where('OwnerType', 8), $this, 'OwnerId', 'Id');
    }

    public function bankAccountTransactions()
    {
        // SELECT FromId, FromType, `From`, FromCash, ToId, ToType, `To`, ToCash, Amount, Reason, Category, Subcategory, Date FROM view_Money WHERE (FromType = 1 AND FromId = {$playerId}) OR (ToType = 1 AND ToId = {$playerId}) ORDER BY Date DESC LIMIT 0, 1000
        return BankAccountTransaction::query()->where('FromId', $this->Id)->where('FromType', 8)->orWhere('ToId', $this->Id)->where('ToType', 8);
    }
}
