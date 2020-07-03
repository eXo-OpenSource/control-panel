<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;
    const DELETED_AT = 'Deleted';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';

    public function members()
    {
        return $this->hasMany(Character::class, 'GroupId', 'Id');
    }

    public function logs()
    {
        return GroupLog::where('GroupType', 'group')->where('GroupId', $this->Id);
    }

    public function getActivity(Carbon $from, Carbon $to)
    {
        return AccountActivityGroup::getActivity($this->Id, 4, $from, $to);
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

    public function bank()
    {
        return $this->morphOne(BankAccount::class, 'bank', 'OwnerType', 'OwnerId', 'Id');
    }

    public function money()
    {
        return BankAccountTransaction::query()->where('FromType', 8)->where('FromId', $this->Id)->orWhere('ToType', 8)->where('ToId', $this->Id);
    }

    public function getMorphClass()
    {
        return 8;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function getURL()
    {
        return route('groups.show', $this->Id);
    }
}
