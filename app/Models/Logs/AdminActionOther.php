<?php

namespace App\Models\Logs;

use App\Models\Company;
use App\Models\Faction;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AdminActionOther extends Model
{
    protected $primaryKey = 'Id';
    protected $connection = 'mysql_logs';
    protected $table = 'AdminActionOther';
    public $timestamps = false;

    protected $dates = ['Date'];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }
}
