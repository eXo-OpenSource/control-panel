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
use TrayLabs\InfluxDB\Facades\InfluxDB;

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

    public static function getStateVsEvilOnline(Carbon $from, Carbon $to)
    {
        $result = [
            'labels' => [],
            'datasets' => [],
        ];

        $factions = Faction::all();
        $factionType = [];

        foreach($factions as $faction) {
            $factionType[$faction->Name] = $faction->Type;
        }


        $playerFactionCount = InfluxDB::query('SELECT mean("total") FROM "user_faction" WHERE ("branch" = \'release/production\') AND time > now() - 1d GROUP BY time(15m), "name" fill(linear)');
        $factionPoints = $playerFactionCount->getPoints();

        $factionsState = [];
        $factionsEvil = [];

        $lastTime = $factionPoints[0]['time'];

        foreach($factionPoints as $point) {
            if($point['time'] < $lastTime) {
                break;
            }
            array_push($result['labels'], (new Carbon($point['time']))->format('Y-m-d H:i'));

            $state = 0;
            $evil = 0;

            foreach($factionPoints as $point2) {
                if($point2['time'] === $point['time']) {
                    $type = $factionType[$point2['name']];
                    if($type === 'State') {
                        $state += $point2['mean'];
                    } elseif($type === 'Evil') {
                        $evil += $point2['mean'];
                    }
                }
            }
            $lastTime = $point['time'];

            array_push($factionsState, round($state, 1));
            array_push($factionsEvil, round($evil, 1));
        }

        $result['datasets'] = [
            [
                'label' => 'Staatsfraktionen',
                'borderColor' => 'rgba(0, 200, 255, 1)',
                'backgroundColor' => 'rgba(0, 200, 255, 0.2)',
                'pointBorderColor' => 'rgba(0, 200, 255, 1)',
                'pointBackgroundColor' => 'rgba(0, 200, 255, 1)',
                'pointHoverBackgroundColor' => 'rgba(0, 200, 255, 1)',
                'pointRadius' => 2.5,
                'data' => $factionsState,
            ],
            [
                'label' => 'Gangs & Mafien',
                'borderColor' => 'rgba(140, 20, 0, 1)',
                'backgroundColor' => 'rgba(140, 20, 0, 0.2)',
                'pointBorderColor' => 'rgba(140, 20, 0, 1)',
                'pointBackgroundColor' => 'rgba(140, 20, 0, 1)',
                'pointHoverBackgroundColor' => 'rgba(140, 20, 0, 1)',
                'pointRadius' => 2.5,
                'data' => $factionsEvil,
            ]
        ];

        return [
            'type' => 'line',
            'data' => $result,
            'options' => [
                'maintainAspectRatio' => false,
                'scales' => [
                    'xAxes' => [
                        [
                            'type' => 'time',
                            'time' => [
                                'parser' => 'YYYY-MM-DD HH:mm',
                                'minUnit' => 'minute',
                                'unit' => 'minute',
                                'stepSize' => 30,
                                'displayFormats' => [
                                    'minute' => 'H:mm',
                                    'hour' => 'H'
                                ]
                            ],
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Uhrzeit'
                            ]
                        ]
                    ],
                    'yAxes' => [
                        [
                            'ticks' => [
                                'min' => 0,
                            ],
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Spieler online'
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

    public static function getStateVsEvilRelativeOnline(Carbon $from, Carbon $to)
    {
        $factions = Faction::all();

        $state = 0;
        $evil = 0;

        foreach($factions as $faction) {
            switch($faction->Type) {
                case 'State':
                    $state += $faction->membersCount();
                    break;
                case 'Evil':
                    $evil += $faction->membersCount();
                    break;
            }
        }

        $result = self::getStateVsEvilOnline($from, $to);

        foreach($result['data']['datasets'][0]['data'] as $dataKey => $value) {
            $result['data']['datasets'][0]['data'][$dataKey] = round($value / $state, 1);
        }

        foreach($result['data']['datasets'][1]['data'] as $dataKey => $value) {
            $result['data']['datasets'][1]['data'][$dataKey] = round($value / $evil, 1);
        }

        $result['options']['scales']['yAxes'][0]['scaleLabel']['labelString'] = 'Spieler online/Anzahl Mitglieder';

        return $result;
    }

    public static function getTotalOnline(Carbon $from, Carbon $to)
    {
        $result = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Spieler online',
                    'borderColor' => 'rgba(0, 200, 255, 1)',
                    'backgroundColor' => 'rgba(0, 200, 255, 0.2)',
                    'pointBorderColor' => 'rgba(0, 200, 255, 1)',
                    'pointBackgroundColor' => 'rgba(0, 200, 255, 1)',
                    'pointHoverBackgroundColor' => 'rgba(0, 200, 255, 1)',
                    'pointRadius' => 2.5,
                    'data' => [],
                ]
            ],
        ];

        $playerCount = InfluxDB::query('select max("loggedIn") from user_total WHERE ("branch" = \'release/production\') AND time > now() - 7d GROUP BY time(30m) fill(linear)');
        $points = $playerCount->getPoints();

        foreach($points as $point) {
            array_push($result['labels'], (new Carbon($point['time']))->format('Y-m-d H:i'));
            array_push($result['datasets'][0]['data'], round($point['max'], 1));
        }

        return [
            'type' => 'line',
            'data' => $result,
            'options' => [
                'maintainAspectRatio' => false,
                'scales' => [
                    'xAxes' => [
                        [
                            'type' => 'time',
                            'time' => [
                                'parser' => 'YYYY-MM-DD HH:mm',
                                'minUnit' => 'hour',
                                'unit' => 'hour',
                                'stepSize' => 4,
                                'displayFormats' => [
                                    'minute' => 'H:mm',
                                    'hour' => 'ddd H:mm'
                                ]
                            ],
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Datum'
                            ]
                        ]
                    ],
                    'yAxes' => [
                        [
                            'ticks' => [
                                'min' => 0,
                            ],
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Spieler online'
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
}
