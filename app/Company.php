<?php

namespace App;

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
}
