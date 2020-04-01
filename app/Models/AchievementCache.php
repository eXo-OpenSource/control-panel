<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AchievementCache extends Model
{
    protected $table = 'achievementCache';
    protected $primaryKey = 'AchievementId';

    public function achievement()
    {
        return $this->hasOne(Achievement::class, 'id', 'AchievementId');
    }
}
