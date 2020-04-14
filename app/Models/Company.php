<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $primaryKey = 'Id';


    public function members()
    {
        return $this->hasMany(Character::class, 'CompanyId', 'Id');
    }

    public function membersCount()
    {
        return Character::where('CompanyId', $this->Id)->count();
    }

    public function logs()
    {
        return GroupLog::where('GroupType', 'company')->where('GroupId', $this->Id);
    }

    public function getActivity(Carbon $from, Carbon $to)
    {
        return AccountActivityGroup::getActivity($this->Id, 3, $from, $to);
    }

    public function getColor($alpha = 1)
    {
        $color = config('constants.companyColors')[0];

        if (config('constants.companyColors')[$this->Id]) {
            $color = config('constants.companyColors')[$this->Id];
        }

        return "rgba(".$color[0].", ".$color[1].", ".$color[2].", ".$alpha.")";
    }

    public function money()
    {
        return BankAccountTransaction::query()->where('FromType', 3)->where('FromId', $this->Id)->orWhere('ToType', 3)->where('ToId', $this->Id);
    }

    public function vehicles()
    {
        return $this->newHasMany(Vehicle::where('OwnerType', 3), $this, 'OwnerId', 'Id');
    }

    public function bank()
    {
        return $this->morphOne(BankAccount::class, 'bank', 'OwnerType', 'OwnerId', 'Id');
    }

    public function getMorphClass()
    {
        return 3;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function getURL()
    {
        return route('companies.show', $this->Id);
    }
}
