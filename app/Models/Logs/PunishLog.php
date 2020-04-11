<?php

namespace App\Models\Logs;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PunishLog extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'PunishLog';
    protected $connection = 'mysql_logs';
    public $timestamps = false;

    protected $dates = ['Date'];

    public function punish()
    {
        return $this->hasOne(Punish::class, 'Id', 'PunishId');
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'Id', 'AdminId');
    }
}
