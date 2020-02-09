<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    protected $primaryKey = 'Id';

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'Id');
    }
}
