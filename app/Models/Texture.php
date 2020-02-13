<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Texture extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'textureshop';
    public $timestamps = false;
    protected $dates = ['Date'];

    public function getStatus()
    {
        if ($this->Status === 0) {
            return 'Testmodus';
        } else if ($this->Status === 1) {
            return 'in Bearbeitung';
        } else if ($this->Status === 2) {
            return 'Freigeschalten';
        } else {
            return 'Abgelehnt';
        }
    }

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'Id', 'Admin');
    }

    public function isDeleteable()
    {
        return $this->Status !== 2;
    }
}
