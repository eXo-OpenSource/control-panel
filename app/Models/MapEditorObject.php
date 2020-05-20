<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MapEditorObject extends Model
{
    protected $table = 'map_editor_objects';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';
    public $timestamps = false;

    public function creator()
    {
        return $this->hasOne(User::class, 'Id', 'Creator');
    }

    public function map()
    {
        return $this->hasOne(MapEditorMap::class, 'Id', 'MapId');
    }
}
