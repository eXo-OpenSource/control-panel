<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AchievementCache extends Model
{
    protected $table = 'AchievementCache';
    protected $primaryKey = 'AchievementId';

    public function achievement()
    {
        return $this->hasOne(Achievement::class, 'id', 'AchievementId');
    }
}
