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
                'fill' => false,
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
                ],
                'hover' => [
                    'mode' => 'nearest',
                    'intersect' => true
                ],
                'tooltips' => [
                    'mode' => 'index',
                    'intersect' => false
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
                'fill' => false,
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
                ],
                'hover' => [
                    'mode' => 'nearest',
                    'intersect' => true
                ],
                'tooltips' => [
                    'mode' => 'index',
                    'intersect' => false
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
                ],
                'hover' => [
                    'mode' => 'nearest',
                    'intersect' => true
                ],
                'tooltips' => [
                    'mode' => 'index',
                    'intersect' => false
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
                ],
                'hover' => [
                    'mode' => 'nearest',
                    'intersect' => true
                ],
                'tooltips' => [
                    'mode' => 'index',
                    'intersect' => false
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
                ],
                'hover' => [
                    'mode' => 'nearest',
                    'intersect' => true
                ],
                'tooltips' => [
                    'mode' => 'index',
                    'intersect' => false
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
                ],
                'hover' => [
                    'mode' => 'nearest',
                    'intersect' => true
                ],
                'tooltips' => [
                    'mode' => 'index',
                    'intersect' => false
                ]
            ],
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'status' => 'Success'
        ];
    }

    public static function getMoney(?Model $object, Carbon $from, Carbon $to)
    {
        $days = $from->diffInDays($to);

        $labels = [];

        for ($i = 0; $i <= $days; $i++) {
            $date = $from->copy()->addDays($i)->format('Y-m-d');
            array_push($labels, $date);
        }

        if($object !== null) {
            $bankAccount = -1;

            if($object instanceof Faction) {
                if($object->Id === 2 || $object->Id === 3) {
                    $bankAccount = Faction::find(1)->bank->Id;
                } elseif($object->bank) {
                    $bankAccount = $object->bank->Id;
                }
            } else {
                if($object->bank) {
                    $bankAccount = $object->bank->Id;
                }
            }

            if($bankAccount === -1) {
                return ['status' => 'Error'];
            }

            if(!Cache::has('bank:in-out:' . $bankAccount) || true) {
                $inValues = [];
                $outValues = [];
                $in = DB::connection('mysql_logs')->select('SELECT DATE(Date) AS Date, SUM(Amount) AS Amount FROM vrpLogs_MoneyNew WHERE ToBank = ? AND DateFormatted BETWEEN DATE(?) AND DATE(?) GROUP BY DATE(Date)', [$bankAccount, $from, $to]);
                $out = DB::connection('mysql_logs')->select('SELECT DATE(Date) AS Date, SUM(Amount) AS Amount FROM vrpLogs_MoneyNew WHERE FromBank = ? AND DateFormatted BETWEEN DATE(?) AND DATE(?) GROUP BY DATE(Date)', [$bankAccount, $from, $to]);

                foreach($labels as $date) {
                    $gotValue = false;
                    foreach($in as $entry) {
                        if($date === $entry->Date) {
                            array_push($inValues, $entry->Amount);
                            $gotValue = true;
                            break;
                        }
                    }

                    if(!$gotValue) {
                        array_push($inValues, 0);
                    }

                    $gotValue = false;
                    foreach($out as $entry) {
                        if($date === $entry->Date) {
                            array_push($outValues, $entry->Amount);
                            $gotValue = true;
                            break;
                        }
                    }

                    if(!$gotValue) {
                        array_push($outValues, 0);
                    }
                }


                Cache::put('bank:in-out:' . $bankAccount, ['in' => $inValues, 'out' => $outValues], Carbon::now()->addMinutes(15));
            }

            $data = Cache::get('bank:in-out:' . $bankAccount);
        }

        return [
            'type' => 'bar',
            'data' => [
                'datasets' => [
                    [
                        'label' => 'Einnahmen',
                        'data' => $data['in'],
                        'backgroundColor' => 'rgba(69, 161, 100, 1)',
                        'borderWidth' => 0,
                    ],
                    [
                        'label' => 'Ausgaben',
                        'data' => $data['out'],
                        'backgroundColor' => 'rgba(209, 103, 103, 1)',
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

    public static function getMoneyDetails(?Model $object, string $direction, Carbon $date)
    {
        $allowedColors = [
            'rgb(54, 162, 235)',
            'rgb(75, 192, 192)',
            'rgb(201, 203, 207)',
            'rgb(255, 159, 64)',
            'rgb(153, 102, 255)',
            'rgb(255, 99, 132)',
            'rgb(255, 205, 86)',
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

            $cacheKey = 'bank:' . $direction . ' details-' . $date->format('Y-m-d') . ':' . $bankAccount;

            if(!Cache::has($cacheKey)) {
                $labels = [];
                $values = [];
                $colors = [];
                $data = [];

                if($direction === 'in') {
                    $data = DB::connection('mysql_logs')->select('SELECT Category, Subcategory, SUM(Amount) AS Amount FROM vrpLogs_MoneyNew WHERE ToBank = ? AND DateFormatted = ? GROUP BY Category, Subcategory', [$bankAccount, $date->format('Y-m-d')]);
                } else {
                    $data = DB::connection('mysql_logs')->select('SELECT Category, Subcategory, SUM(Amount) AS Amount FROM vrpLogs_MoneyNew WHERE FromBank = ? AND DateFormatted = ? GROUP BY Category, Subcategory', [$bankAccount, $date->format('Y-m-d')]);
                }


                foreach($data as $entry)
                {
                    array_push($labels, $entry->Category . ', ' . $entry->Subcategory);
                    array_push($values, $entry->Amount);

                    if(count($allowedColors) > 0) {
                        $index = rand(0, count($allowedColors) - 1);
                        array_push($colors, $allowedColors[$index]);
                        unset($allowedColors[$index]);
                        sort($allowedColors);
                    } else {
                        array_push($colors, 'rgba('.rand(0, 255).', '.rand(0, 255).', '.rand(0, 255).', 1)');
                    }
                }


                Cache::put($cacheKey, ['labels' => $labels, 'values' => $values, 'colors' => $colors], Carbon::now()->addMinutes(15));
            }

            $data = Cache::get($cacheKey);
        }

        return [
            'type' => 'doughnut',
            'data' => [
                'datasets' => [
                    [
                        'label' => $direction === 'in' ? 'Einnahmen' : 'Ausgaben',
                        'data' => $data['values'] ?? [],
                        'backgroundColor' => $data['colors'] ?? [],
                        'borderWidth' => 1,
                    ]
                ],
                'labels' => $data['labels'] ?? []
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
            'date' => $date->format('Y-m-d'),
            'tooltips' => 'money',
            'status' => 'Success'
        ];
    }

    public static function getMoneyAdmin(Carbon $from, Carbon $to)
    {
        if(!Cache::has('bank:in-out:overall')) {
            $in = DB::connection('mysql_logs')->select('SELECT SUM(Amount) AS Amount FROM vrpLogs_MoneyNew WHERE (FromType = 4 OR FromType = 5 OR FromType = 9) AND DateFormatted BETWEEN ? AND ?', [$from->format('Y-m-d'), $to->format('Y-m-d')])[0]->Amount;
            $out = DB::connection('mysql_logs')->select('SELECT SUM(Amount) AS Amount FROM vrpLogs_MoneyNew WHERE (ToType = 4 OR ToType = 5 OR ToType = 9) AND DateFormatted BETWEEN ? AND ?', [$from->format('Y-m-d'), $to->format('Y-m-d')])[0]->Amount;
            Cache::put('bank:in-out:overall', ['in' => $in, 'out' => $out], Carbon::now()->addMinutes(15));
        }
        $data = Cache::get('bank:in-out:overall');

        $labels = [
            'Erschaffen',
            'Zerstört',
        ];

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
                    ],
                ],
                'showAllTooltips' => true
            ],
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'tooltips' => 'money',
            'status' => 'Success'
        ];
    }

    public static function getMoneyAdminDaily(Carbon $from, Carbon $to)
    {
        $days = $from->diffInDays($to);

        $labels = [];

        for ($i = 0; $i <= $days; $i++) {
            $date = $from->copy()->addDays($i)->format('Y-m-d');
            array_push($labels, $date);
        }

        if(!Cache::has('bank:in-out:daily')) {
            $inValues = [];
            $outValues = [];
            $in = DB::connection('mysql_logs')->select('SELECT DateFormatted, SUM(Amount) AS Amount FROM vrpLogs_MoneyNew WHERE (FromType = 4 OR FromType = 5 OR FromType = 9) AND DateFormatted BETWEEN ? AND ? GROUP BY DateFormatted', [$from->format('Y-m-d'), $to->format('Y-m-d')]);
            $out = DB::connection('mysql_logs')->select('SELECT DateFormatted, SUM(Amount) AS Amount FROM vrpLogs_MoneyNew WHERE (ToType = 4 OR ToType = 5 OR ToType = 9) AND DateFormatted BETWEEN ? AND ? GROUP BY DateFormatted', [$from->format('Y-m-d'), $to->format('Y-m-d')]);


            foreach($labels as $date) {
                $gotValue = false;
                foreach ($in as $entry) {
                    if ($date === $entry->DateFormatted) {
                        array_push($inValues, $entry->Amount);
                        $gotValue = true;
                        break;
                    }
                }

                if (!$gotValue) {
                    array_push($inValues, 0);
                }

                $gotValue = false;
                foreach ($out as $entry) {
                    if ($date === $entry->DateFormatted) {
                        array_push($outValues, $entry->Amount);
                        $gotValue = true;
                        break;
                    }
                }

                if (!$gotValue) {
                    array_push($outValues, 0);
                }
            }
            Cache::put('bank:in-out:daily', ['in' => $inValues, 'out' => $outValues], Carbon::now()->addMinutes(30));
        }
        $data = Cache::get('bank:in-out:daily');

        return [
            'type' => 'bar',
            'data' => [
                'datasets' => [
                    [
                        'label' => 'Erschaffen',
                        'data' => $data['in'],
                        'backgroundColor' => 'rgba(69, 161, 100, 1)',
                        'borderWidth' => 0,
                    ],
                    [
                        'label' => 'Zerstört',
                        'data' => $data['out'],
                        'backgroundColor' => 'rgba(209, 103, 103, 1)',
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
                    ],
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
                'fill' => false,
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
                'fill' => false,
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
                ],
                'hover' => [
                    'mode' => 'nearest',
                    'intersect' => true
                ],
                'tooltips' => [
                    'mode' => 'index',
                    'intersect' => false
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
            $result['data']['datasets'][0]['data'][$dataKey] = round($value / $state, 2);
        }

        foreach($result['data']['datasets'][1]['data'] as $dataKey => $value) {
            $result['data']['datasets'][1]['data'][$dataKey] = round($value / $evil, 2);
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
