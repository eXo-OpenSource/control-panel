@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        @if(env('TEAMSPEAK_TROLL_ENABLED') === true)
        <div class="row">
            <div class="col-12 mb-4">
                <a href="/{{ env('TEAMSPEAK_TROLL_URI') }}" class="btn btn-primary btn-danger">{{ env('TEAMSPEAK_TROLL_NAME') }}</a>
            </div>
        </div>
        @endif
        <div class="row mb-2">
            <div class="col-12">
                <span class="h2">{{ __('Event: 1.5x Lohn bei den Jobs vom 15.05. 5 Uhr bis 18.05. 5 Uhr') }}</span>
            </div>
        </div>
        @php
            use Illuminate\Support\Facades\DB;
            $total = DB::connection('mysql_logs')->select('SELECT SUM(Earned) AS SumEarned, SUM(Duration) AS SumDuration FROM vrpLogs_Job WHERE ID > ? AND Date BETWEEN ? AND ?;', [1473351, '2020-05-15 05:00:00', '2020-05-18 05:00:00']);
            $top = DB::connection('mysql_logs')->select('SELECT j.UserID, a.Name, SUM(j.Earned) AS SumEarned FROM vrpLogs_Job j INNER JOIN ' . config('database.connections.mysql.database') . '.vrp_account a ON a.Id = j.UserID WHERE j.ID > ? AND j.Date BETWEEN ? AND ? GROUP BY j.UserID ORDER BY SumEarned DESC LIMIT 10;', [1473351, '2020-05-15 05:00:00', '2020-05-18 05:00:00']);
            $topJob = DB::connection('mysql_logs')->select('SELECT Job, SUM(Earned) AS SumEarned, SUM(Duration) AS SumDuration FROM vrpLogs_Job WHERE ID > ? AND Date BETWEEN ? AND ? GROUP BY Job ORDER BY SumEarned DESC LIMIT 10;', [1473351, '2020-05-15 05:00:00', '2020-05-18 05:00:00']);

            if(auth()->user()) {
                $myData = DB::connection('mysql_logs')->select('SELECT SUM(Earned) AS SumEarned, SUM(Duration) AS SumDuration FROM vrpLogs_Job WHERE ID > ? AND UserID = ? AND Date BETWEEN ? AND ?;', [1473351, auth()->user()->Id, '2020-05-15 05:00:00', '2020-05-18 05:00:00']);
                $myPos = DB::connection('mysql_logs')->select('SELECT SUM(Earned) AS SumEarned FROM vrpLogs_Job WHERE ID > ? AND UserID > ? AND Date BETWEEN ? AND ? GROUP BY UserID ORDER BY SumEarned;', [1473351, auth()->user()->Id, '2020-05-15 05:00:00', '2020-05-18 05:00:00']);
            }

            $jobs = [
              'jobBoxer' => 'Boxer',
              'jobFarmer.combine' => 'Farmer: Mähdrescher',
              'jobFarmer.tractor' => 'Farmer: Traktor',
              'jobFarmer.transport' => 'Farmer: Walton',
              'jobForkLift' => 'Gabelstapler-Fahrer',
              'jobGravel.dozer' => 'Kiesgruben: Dozer',
              'jobGravel.dumper' => 'Kiesgruben: Dumper',
              'jobGravel.mining' => 'Kiesgruben: Bergbau',
              'jobHeliTransport' => 'Helikopterpilot',
              'jobLogistician' => 'Logistik',
              'jobLumberjack' => 'Holzfäller',
              'jobPizzaDelivery' => 'Pizza-Lieferant',
              'jobRoadSweeper' => 'Straßenkehrer',
              'jobTrashman' => 'Müllmann',
              'jobTreasureSeeker' => 'Schatzsucher',
            ];
        @endphp
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="text-muted small text-uppercase font-weight-bold">{{ __('Dollar verdient') }}</div>
                        <div class="text-value-xl py-3">@money($total[0]->SumEarned)</div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="text-muted small text-uppercase font-weight-bold">{{ __('Zeit die mit den Jobs verbracht wurde') }}</div>
                        <div class="text-value-xl py-3">{{ Carbon\Carbon::now()->longAbsoluteDiffForHumans(Carbon\Carbon::now()->addSeconds($total[0]->SumDuration), 5) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Top Verdiener') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Betrag') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($top as $key => $row)
                                <tr @if(auth()->user() && $row->UserID && auth()->user()->Id == $row->UserID)class="table-active"@endif>
                                    <td>{{$key+1}}.</td>
                                    <td><a href="{{ route('users.show', [$row->UserID]) }}">{{ $row->Name }}</a></td>
                                    <td>@money($row->SumEarned)</td>
                                </tr>
                            @endforeach
                            @if($myPos && $myData && count($myPos) > 10)
                                <tr class="table-active">
                                    <td>{{ count($myPos) }}.</td>
                                    <td><a href="{{ route('users.show', [auth()->user()->Id]) }}">{{ auth()->user()->Name }}</a></td>
                                    <td>@money($myData[0]->SumEarned)</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Top Jobs') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('Job') }}</th>
                                <th scope="col">{{ __('Betrag') }}</th>
                                <th scope="col">{{ __('Zeit') }}</th>
                                <th scope="col">{{ __('Betrag pro Minute') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($topJob as $key => $row)
                                <tr>
                                    <td>{{$key+1}}.</td>
                                    <td>{{ $jobs[$row->Job] }}</td>
                                    <td>@money($row->SumEarned)</td>
                                    <td>{{ Carbon\Carbon::now()->longAbsoluteDiffForHumans(Carbon\Carbon::now()->addSeconds($row->SumDuration), 3) }}</td>
                                    <td>@money(($row->SumEarned / $row->SumDuration) * 60)</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-12">
                <react-chart data-chart="activity:factions" data-state="true" data-title="Aktivität"></react-chart>
            </div>
            <div class="col-xl-6 col-lg-12">
                <react-chart data-chart="activity:companies" data-state="true" data-title="Aktivität"></react-chart>
            </div>
            <div class="col-xl-6 col-lg-12">
                <react-chart data-chart="online:statevsevil" data-state="true" data-title="Aktivität Staatsfraktionen vs Mafien & Gangs"></react-chart>
            </div>
            <div class="col-xl-6 col-lg-12">
                <react-chart data-chart="online:statevsevilrelative" data-state="true" data-title="Relative Aktivität Staatsfraktionen vs Mafien & Gangs zu Anzahl Mitglieder"></react-chart>
            </div>
            <div class="col-xl-6 col-lg-12">
                <react-chart data-chart="online:total" data-state="true" data-title="Spieler online"></react-chart>
            </div>
        </div>
    </div>
@endsection
