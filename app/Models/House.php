<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    protected $primaryKey = 'Id';

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'owner');
    }

    public function bank()
    {
        return $this->morphOne(BankAccount::class, 'bank', 'OwnerType', 'OwnerId', 'Id');
    }

    public function getName()
    {
        return 'Haus' . $this->Id;
    }

    public function getURL()
    {
        return null;
    }
}
