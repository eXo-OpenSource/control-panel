<?php

namespace App\Models\Logs;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MagicButton extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'MagicButton';
    protected $connection = 'mysql_logs';
    public const CREATED_AT = 'CreatedAt';
    public const UPDATED_AT = 'UpdatedAt';
    public $timestamps = true;

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }
}
