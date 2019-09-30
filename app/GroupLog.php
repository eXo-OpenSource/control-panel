<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GroupLog extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'Groups';
    protected $connection = 'mysql_logs';
    public $timestamps = false;


    public function user()
    {
        return $this->hasOne(Character::class, 'Id', 'UserId');
    }

    public function getUser()
    {
        return DB::table('account')->where('Id', $this->UserId)->first();
    }
}
