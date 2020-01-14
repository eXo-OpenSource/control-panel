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
        return $this->hasOne(Character::class, 'Id', 'UserId');
    }

    public function getUser()
    {
        return DB::table('account')->where('Id', $this->UserId)->first();
    }

    public function admin()
    {
        return $this->hasOne(Character::class, 'Id', 'AdminId');
    }

    public function getAdmin()
    {
        return DB::table('account')->where('Id', $this->AdminId)->first();
    }
}
