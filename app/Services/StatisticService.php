<?php


namespace App\Services;


use App\Models\Company;
use App\Models\Faction;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatisticService
{

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
            'type' => 'line',
            'data' => $result,
            'options' => [
                'maintainAspectRatio' => false,
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'min' => 0,
                            ]
                        ]
                    ]
                ]
            ],
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
            'type' => 'line',
            'data' => $result,
            'options' => [
                'maintainAspectRatio' => false,
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'min' => 0,
                            ]
                        ]
                    ]
                ]
            ],
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
            'type' => 'line',
            'data' => $result,
            'options' => [
                'maintainAspectRatio' => false,
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'min' => 0,
                            ]
                        ]
                    ]
                ]
            ],
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
            'type' => 'line',
            'data' => $result,
            'options' => [
                'maintainAspectRatio' => false,
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'min' => 0,
                            ]
                        ]
                    ]
                ]
            ],
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
            'type' => 'line',
            'data' => $result,
            'options' => [
                'maintainAspectRatio' => false,
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'min' => 0,
                            ]
                        ]
                    ]
                ]
            ],
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
            'type' => 'line',
            'data' => $result,
            'options' => [
                'maintainAspectRatio' => false,
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'min' => 0,
                            ]
                        ]
                    ]
                ]
            ],
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'status' => 'Success'
        ];
    }

    public static function getMoney(?Model $object, Carbon $from, Carbon $to)
    {
        $data = ['in' => 0, 'out' => 0];
        $labels = [
            'Einnahmen',
            'Ausgaben',
        ];

        if($object !== null) {
            $bankAccount = -1;

            if($object->bank) {
                $bankAccount = $object->bank->Id;
            } else {
                if($object instanceof Faction) {
                    if($object->Id === 2 || $object->Id === 3) {
                        $bankAccount = Faction::find(1)->bank->Id;
                    }
                } else {
                    return ['status' => 'Error'];
                }
            }

            if(!Cache::has('bank:in-out:' . $bankAccount)) {
                $in = DB::connection('mysql_logs')->select('SELECT SUM(Amount) AS Amount FROM vrpLogs_MoneyNew WHERE ToBank = ? AND Date BETWEEN ? AND ?', [$bankAccount, $from, $to])[0]->Amount;
                $out = DB::connection('mysql_logs')->select('SELECT SUM(Amount) AS Amount FROM vrpLogs_MoneyNew WHERE FromBank = ? AND Date BETWEEN ? AND ?', [$bankAccount, $from, $to])[0]->Amount;
                Cache::put('bank:in-out:' . $bankAccount, ['in' => $in, 'out' => $out], Carbon::now()->addMinutes(15));
            }

            $data = Cache::get('bank:in-out:' . $bankAccount);
        } else {

            if(!Cache::has('bank:in-out:overall')) {
                $in = DB::connection('mysql_logs')->select('SELECT SUM(Amount) AS Amount FROM vrpLogs_MoneyNew WHERE (FromType = 4 OR FromType = 5 OR FromType = 9) AND Date BETWEEN ? AND ?', [$from, $to])[0]->Amount;
                $out = DB::connection('mysql_logs')->select('SELECT SUM(Amount) AS Amount FROM vrpLogs_MoneyNew WHERE (ToType = 4 OR ToType = 5 OR ToType = 9) AND Date BETWEEN ? AND ?', [$from, $to])[0]->Amount;
                Cache::put('bank:in-out:overall', ['in' => $in, 'out' => $out], Carbon::now()->addMinutes(15));
            }
            $data = Cache::get('bank:in-out:overall');

            $labels = [
                'Erschaffen',
                'Zerstört',
            ];
        }

        return [
            'type' => 'doughnut',
            'data' => [
                'datasets' => [
                    [
                        'data' => [
                            $data['in'],
                            $data['out'],
                        ],
                        'backgroundColor' => ['rgba(69, 161, 100, 1)', 'rgba(209, 103, 103, 1)'],
                        'borderWidth' => 0,
                    ]
                ],
                'labels' => $labels
            ],
            'options' => [
                'maintainAspectRatio' => false,
                'legend' => [
                    'display' => true,
                    'labels' => [
                        'fontColor' => 'rgba(255, 255, 255, 1)'
                    ]
                ]
            ],
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'tooltips' => 'money',
            'status' => 'Success'
        ];
    }
}
