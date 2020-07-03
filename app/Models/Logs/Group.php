<?php

namespace App\Models\Logs;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'Groups';
    protected $connection = 'mysql_logs';
    public $timestamps = false;

    protected $dates = ['Date'];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function group()
    {
        return $this->morphTo('group', 'GroupType', 'GroupId', 'Id'); // , 'OwnerType', 'Id', 'OwnerId');
    }
}
