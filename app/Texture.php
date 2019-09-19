<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Texture extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'textureshop';
    public $timestamps = false;

    public function getStatus()
    {
        if ($this->Status === 0) {
            return 'Teststatus';
        } else if ($this->Status === 1) {
            return 'in Bearbeitung';
        } else if ($this->Status === 2) {
            return 'Freigeschalten';
        } else {
            return 'Abgelehnt';
        }
    }

    public function isDeleteable()
    {
        return $this->Status !== 2;
    }
}
