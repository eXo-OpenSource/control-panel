<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $primaryKey = 'Id';

    public function members()
    {
        return $this->hasMany(Character::class, 'CompanyId', 'Id');
    }

    public function membersCount()
    {
        return Character::where('CompanyId', $this->Id)->count();
    }

    public function logs()
    {
        return GroupLog::where('GroupType', 'company')->where('GroupId', $this->Id);
    }

    public function getActivity($chart)
    {
        $members = $this->members->pluck('Id')->toArray();
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
        $color = config('constants.companyColors')[0];

        if (config('constants.companyColors')[$this->Id]) {
            $color = config('constants.companyColors')[$this->Id];
        }

        return "rgba(".$color[0].", ".$color[1].", ".$color[2].", ".$alpha.")";
    }
}
