<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $primaryKey = 'Id';

    public function getName()
    {
        return $this->Name;
    }

    public function getURL()
    {
        return null;
    }
}
