<?php

namespace App\Models\Logs;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Money extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'MoneyNew';
    protected $connection = 'mysql_logs';
    public $timestamps = false;

    protected $dates = ['Date'];

    public function from()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function to()
    {
        return $this->hasOne(User::class, 'Id', 'AdminId');
    }
}
