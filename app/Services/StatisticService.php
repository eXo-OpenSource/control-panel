<?php


namespace App\Services;


use App\Models\Company;
use App\Models\Faction;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;

class StatisticService
{
    /**
    label: 'My Second dataset',
    fill: false,
    lineTension: 0.1,
    backgroundColor: 'rgba(136,71,192,0.4)',
    borderColor: 'rgb(167,76,192)',
    borderCapStyle: 'butt',
    borderDash: [],
    borderDashOffset: 0.0,
    borderJoinStyle: 'miter',
    pointBorderColor: 'rgb(167,76,192))',
    pointBackgroundColor: 'rgb(167,76,192)',
    pointBorderWidth: 1,
    pointHoverRadius: 5,
    pointHoverBackgroundColor: 'rgb(167,76,192)',
    pointHoverBorderColor: 'rgba(220,220,220,1)',
    pointHoverBorderWidth: 2,
    pointRadius: 1,
    pointHitRadius: 10,
     */

    public static function getFactionsActivity(Carbon $from, Carbon $to)
    {
        /** @var \App\Models\Faction[] $factions */
        $factions = Faction::where('active', 1)->get();

        $result = [
            'labels' => [],
            'datasets' => [],
        ];

        foreach ($factions as $key => $faction) {
            $activity = $faction->getActivity($from, $to);

            $data = [
                'label' => $faction->Name . ": Aktivität (h)",
                'borderColor' => $faction->getColor(),
                'backgroundColor' => $faction->getColor(0.2),
                'pointBorderColor' => $faction->getColor(),
                'pointBackgroundColor' => $faction->getColor(),
                'pointHoverBackgroundColor' => $faction->getColor(),
                'data' => [],
            ];

            foreach ($activity as $entry) {
                if ($key === 1) {
                    array_push($result['labels'], $entry->Date);
                }

                array_push($data['data'], round($entry->DurationDuty / 60, 1));
            }

            array_push($result['datasets'], $data);
        }


        return [
            'chart' => $result,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'status' => 'Success'
        ];
    }
    public static function getCompaniesActivity(Carbon $from, Carbon $to)
    {
        /** @var \App\Models\Company[] $companies */
        $companies = Company::all();

        $result = [
            'labels' => [],
            'datasets' => [],
        ];

        foreach ($companies as $key => $company) {
            $activity = $company->getActivity($from, $to);

            $data = [
                'label' => $company->Name . ": Aktivität (h)",
                'borderColor' => $company->getColor(),
                'backgroundColor' => $company->getColor(0.2),
                'pointBorderColor' => $company->getColor(),
                'pointBackgroundColor' => $company->getColor(),
                'pointHoverBackgroundColor' => $company->getColor(),
                'data' => [],
            ];

            foreach ($activity as $entry) {
                if ($key === 1) {
                    array_push($result['labels'], $entry->Date);
                }

                array_push($data['data'], round($entry->DurationDuty / 60, 1));
            }

            array_push($result['datasets'], $data);
        }


        return [
            'chart' => $result,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'status' => 'Success'
        ];
    }

    public static function getFactionActivity(Faction $faction, Carbon $from, Carbon $to)
    {
        $result = [
            'labels' => [],
            'datasets' => [],
        ];

        $activity = $faction->getActivity($from, $to);

        $data = [
            'label' => 'Aktivität (h)',
            'borderColor' => 'rgba(165, 170, 170, 1)',
            'backgroundColor' => 'rgba(165, 170, 170, 0.2)',
            'pointBorderColor' => 'rgba(165, 170, 170, 1)',
            'pointBackgroundColor' => 'rgba(165, 170, 170, 1)',
            'pointHoverBackgroundColor' => 'rgba(165, 170, 170, 1)',
            'data' => [],
        ];

        $dataDuty = [
            'label' => 'Aktivität im Dienst (h)',
            'borderColor' => $faction->getColor(),
            'backgroundColor' => $faction->getColor(0.2),
            'pointBorderColor' => $faction->getColor(),
            'pointBackgroundColor' => $faction->getColor(),
            'pointHoverBackgroundColor' => $faction->getColor(),
            'data' => [],
        ];

        foreach ($activity as $entry) {
            array_push($result['labels'], $entry->Date);

            array_push($data['data'], round($entry->Duration / 60, 1));
            array_push($dataDuty['data'], round($entry->DurationDuty / 60, 1));
        }

        array_push($result['datasets'], $data);
        array_push($result['datasets'], $dataDuty);

        return [
            'chart' => $result,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'status' => 'Success'
        ];
    }

    public static function getCompanyActivity(Company $company, Carbon $from, Carbon $to)
    {
        $result = [
            'labels' => [],
            'datasets' => [],
        ];

        $activity = $company->getActivity($from, $to);

        $data = [
            'label' => 'Aktivität (h)',
            'borderColor' => 'rgba(165, 170, 170, 1)',
            'backgroundColor' => 'rgba(165, 170, 170, 0.2)',
            'pointBorderColor' => 'rgba(165, 170, 170, 1)',
            'pointBackgroundColor' => 'rgba(165, 170, 170, 1)',
            'pointHoverBackgroundColor' => 'rgba(165, 170, 170, 1)',
            'data' => [],
        ];

        $dataDuty = [
            'label' => 'Aktivität im Dienst (h)',
            'borderColor' => $company->getColor(),
            'backgroundColor' => $company->getColor(0.2),
            'pointBorderColor' => $company->getColor(),
            'pointBackgroundColor' => $company->getColor(),
            'pointHoverBackgroundColor' => $company->getColor(),
            'data' => [],
        ];

        foreach ($activity as $entry) {
            array_push($result['labels'], $entry->Date);

            array_push($data['data'], round($entry->Duration / 60, 1));
            array_push($dataDuty['data'], round($entry->DurationDuty / 60, 1));
        }

        array_push($result['datasets'], $data);
        array_push($result['datasets'], $dataDuty);

        return [
            'chart' => $result,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'status' => 'Success'
        ];
    }

    public static function getGroupActivity(Group $group, Carbon $from, Carbon $to)
    {
        $result = [
            'labels' => [],
            'datasets' => [],
        ];

        $activity = $group->getActivity($from, $to);

        $data = [
            'label' => 'Aktivität (h)',
            'borderColor' => 'rgba(165, 170, 170, 1)',
            'backgroundColor' => 'rgba(165, 170, 170, 0.2)',
            'pointBorderColor' => 'rgba(165, 170, 170, 1)',
            'pointBackgroundColor' => 'rgba(165, 170, 170, 1)',
            'pointHoverBackgroundColor' => 'rgba(165, 170, 170, 1)',
            'data' => [],
        ];

        foreach ($activity as $entry) {
            array_push($result['labels'], $entry->Date);

            array_push($data['data'], round($entry->Duration / 60, 1));
        }

        array_push($result['datasets'], $data);

        return [
            'chart' => $result,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'status' => 'Success'
        ];
    }

    public static function getUserActivity(User $user, Carbon $from, Carbon $to)
    {
        $result = [
            'labels' => [],
            'datasets' => [],
        ];

        $activity = $user->getActivity($from, $to);

        $data = [
            'label' => 'Aktivität (h)',
            'borderColor' => 'rgba(165, 170, 170, 1)',
            'backgroundColor' => 'rgba(165, 170, 170, 0.2)',
            'pointBorderColor' => 'rgba(165, 170, 170, 1)',
            'pointBackgroundColor' => 'rgba(165, 170, 170, 1)',
            'pointHoverBackgroundColor' => 'rgba(165, 170, 170, 1)',
            'data' => [],
        ];

        $dataDuty = [
            'label' => 'Aktivität im Dienst (h)',
            /*
            'borderColor' => $company->getColor(),
            'backgroundColor' => $company->getColor(0.2),
            'pointBorderColor' => $company->getColor(),
            'pointBackgroundColor' => $company->getColor(),
            'pointHoverBackgroundColor' => $company->getColor(),
            */
            'data' => [],
        ];

        foreach ($activity as $entry) {
            array_push($result['labels'], $entry->Date);

            array_push($data['data'], round($entry->Duration / 60, 1));
            //array_push($dataDuty['data'], round($entry->DurationDuty / 60, 1));
        }

        array_push($result['datasets'], $data);
        //array_push($result['datasets'], $dataDuty);

        return [
            'chart' => $result,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'status' => 'Success'
        ];
    }
}
