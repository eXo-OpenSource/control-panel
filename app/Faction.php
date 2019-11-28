<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Faction extends Model
{
    protected $primaryKey = 'Id';

    public function members()
    {
        return $this->hasMany(Character::class, 'FactionId', 'Id');
    }

    public function membersCount()
    {
        return Character::where('FactionId', $this->Id)->count();
    }

    public function logs()
    {
        return GroupLog::where('GroupType', 'faction')->where('GroupId', $this->Id);
    }

    public function getActivity($chart)
    {
        $members = $this->members->pluck('Id')->toArray();

        /*
                    {
                        label: 'My First dataset',
                        backgroundColor : '@factionColor(4, 0.2)',
                        borderColor : '@factionColor(4)',
                        pointBackgroundColor : '@factionColor(4)',
                        pointBorderColor : '#fff',
                        data : [random(), random(), random(), random(), random(), random(), random()]
                    },
         */

        $data = AccountActivity::getActivity($members, $chart);

        foreach ($data['datasets'] as $key => $value) {
            $data['datasets'][$key]['label'] = $this->Name;
            $data['datasets'][$key]['backgroundColor'] = $this->getColor(0.2);
            $data['datasets'][$key]['borderColor'] = $this->getColor();
            $data['datasets'][$key]['pointBackgroundColor'] = $this->getColor();
            $data['datasets'][$key]['pointBorderColor'] = '#fff';
        }

        return $data;
    }

    public function getColor($alpha = 1)
    {
        $color = config('constants.factionColors')[0];

        if (config('constants.factionColors')[$this->Id]) {
            $color = config('constants.factionColors')[$this->Id];
        }

        return "rgba(".$color[0].", ".$color[1].", ".$color[2].", ".$alpha.")";
    }
}
