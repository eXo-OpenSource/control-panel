<?php

namespace App\Models\Logs;

use App\Models\User;
use App\Models\IpHub;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'Login';
    protected $connection = 'mysql_logs';
    public $timestamps = false;

    protected $dates = ['Date'];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function ipHub()
    {
        return $this->hasOne(IpHub::class, 'Ip', 'Ip');
    }
}
