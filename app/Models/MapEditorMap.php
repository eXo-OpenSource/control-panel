<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MapEditorMap extends Model
{
    protected $table = 'map_editor_maps';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';
    public $timestamps = false;

    public function creator()
    {
        return $this->hasOne(User::class, 'Id', 'Creator');
    }
}
