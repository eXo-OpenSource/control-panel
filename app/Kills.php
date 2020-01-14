<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Kills extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'Kills';
    protected $connection = 'mysql_logs';
    public $timestamps = false;

    protected $dates = ['Date'];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function target()
    {
        return $this->hasOne(User::class, 'Id', 'TargetId');
    }
}
