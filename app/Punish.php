<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Punish extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'Punish';
    protected $connection = 'mysql_logs';
    public $timestamps = false;

    protected $dates = ['Date'];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'Id', 'AdminId');
    }
}