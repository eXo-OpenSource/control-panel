<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;
    const DELETED_AT = 'Deleted';
    protected $primaryKey = 'Id';

    public function getName()
    {
        return config('constants.vehicleNames')[$this->Model];
    }

    public function getTunings()
    {
        $data = json_decode($this->Tunings, true)[0];
        return $data;
    }

    public function getTuningColor($num)
    {
        $tunings = $this->getTunings();

        if($tunings && isset($tunings['Color' . $num])) {
            return sprintf('#%02x%02x%02x', $tunings['Color' . $num][0], $tunings['Color' . $num][1], $tunings['Color' . $num][2]);
        }
        return '#000000';
    }
}
