<?php

namespace App\Models\Logs;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

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
