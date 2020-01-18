<?php

namespace App\Models\Logs;

use App\User;
use Illuminate\Database\Eloquent\Model;

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
