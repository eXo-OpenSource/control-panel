<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamspeakIdentity extends Model
{
    use SoftDeletes;

    protected $table = 'teamspeak_identity';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';
    public const CREATED_AT = 'CreatedAt';
    public const UPDATED_AT = 'UpdatedAt';
    public const DELETED_AT = 'DeletedAt';

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'Id', 'AdminId');
    }

    public function getTypeName()
    {
        if ($this->Type === 1) {
            return 'Benutzer';
        }
        return 'Musikbot';
    }
}
