<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warn extends Model
{
    protected $primaryKey = 'Id';

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'userId');
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'Id', 'adminId');
    }
}
