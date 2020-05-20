@extends('layouts.app')

@section('title', __('Statistiken'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Spielzeit-Statistik') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Spielzeit') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($playTime as $key => $row)
                                <tr @if(auth()->user() && $row->user && auth()->user()->Id == $row->user->Id)class="table-active"@endif>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->user->Id != -1)<a href="{{ route('users.show', [$row->user->Id]) }}">{{ $row->user->Name }}@else{{ $row->user->Name }}@endif</a></td>
                                    <td>{{ $row->getPlayTime() }}</td>
                                </tr>
                            @endforeach
                            @if($playTimeMyPosition && $playTimeMyPosition > 50)
                                <tr class="table-active">
                                    <td>{{ $playTimeMyPosition }}.</td>
                                    <td><a href="{{ route('users.show', [auth()->user()->Id]) }}">{{ auth()->user()->Name }}</a></td>
                                    <td>{{ auth()->user()->character->getPlayTime() }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Bankgeld-Statistik') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Bankgeld') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bankMoney as $key => $row)
                                <tr @if(auth()->user() && $row->owner && auth()->user()->Id == $row->owner->Id)class="table-active"@endif>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->owner->Id != -1)<a href="{{ route('users.show', [$row->owner->Id]) }}">{{ $row->owner->Name }}@else{{ $row->owner->Name }}@endif</a></td>
                                    <td>@money($row->Money)</td>
                                </tr>
                            @endforeach
                            @if($bankMoneyMyPosition && $bankMoneyMyPosition > 50)
                                <tr class="table-active">
                                    <td>{{ $bankMoneyMyPosition }}.</td>
                                    <td><a href="{{ route('users.show', [auth()->user()->Id]) }}">{{ auth()->user()->Name }}</a></td>
                                    <td>@money(auth()->user()->character->bank->Money)</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Fische gefangen') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Fische gefangen') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($fishes as $key => $row)
                                <tr @if(auth()->user() && $row->user && auth()->user()->Id == $row->user->Id)class="table-active"@endif>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->user && $row->user->Id != -1)<a href="{{ route('users.show', [$row->user->Id]) }}">{{ $row->user->Name }}@else{{ $row->user ? $row->user->Name : "unbekannt" }}@endif</a></td>
                                    <td>{{number_format($row->FishCaught, 0, '', '.')}}</td>
                                </tr>
                            @endforeach
                            @if($fishesMyPosition && $fishesMyPosition > 50)
                                <tr class="table-active">
                                    <td>{{ $fishesMyPosition }}.</td>
                                    <td><a href="{{ route('users.show', [auth()->user()->Id]) }}">{{ auth()->user()->Name }}</a></td>
                                    <td>{{ number_format(auth()->user()->character->stats->FishCaught, 0, '', '.') }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Kilometer gefahren') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Kilometer gefahren') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($driven as $key => $row)
                                <tr @if(auth()->user() && $row->user && auth()->user()->Id == $row->user->Id)class="table-active"@endif>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->user && $row->user->Id != -1)<a href="{{ route('users.show', [$row->user->Id]) }}">{{ $row->user->Name }}@else{{ $row->user ? $row->user->Name : "unbekannt" }}@endif</a></td>
                                    <td>{{number_format($row->Driven / 1000, 0, ',', '.')}} km</td>
                                </tr>
                            @endforeach
                            @if($drivenMyPosition && $drivenMyPosition > 50)
                                <tr class="table-active">
                                    <td>{{ $drivenMyPosition }}.</td>
                                    <td><a href="{{ route('users.show', [auth()->user()->Id]) }}">{{ auth()->user()->Name }}</a></td>
                                    <td>{{ number_format(auth()->user()->character->stats->Driven / 1000, 0, ',', '.') }} km</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Gangwar Schaden aktuelle Woche') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Schaden') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($damageCurrentWeek as $key => $row)
                                <tr @if(auth()->user() && auth()->user()->Id === $row->UserId)class="table-active"@endif>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->UserId != -1)<a href="{{ route('users.show', [$row->UserId]) }}">{{ $row->Name }}@else{{ $row->Name }}@endif</a></td>
                                    <td>{{ $row->Amount }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Gangwar Schaden letzte Woche') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Schaden') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($damageLastWeek as $key => $row)
                                <tr @if(auth()->user() && auth()->user()->Id === $row->UserId)class="table-active"@endif>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->UserId != -1)<a href="{{ route('users.show', [$row->UserId]) }}">{{ $row->Name }}@else{{ $row->Name }}@endif</a></td>
                                    <td>{{ $row->Amount }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Gangwar Kills aktuelle Woche') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Kills') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($killsCurrentWeek as $key => $row)
                                <tr @if(auth()->user() && auth()->user()->Id === $row->UserId)class="table-active"@endif>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->UserId != -1)<a href="{{ route('users.show', [$row->UserId]) }}">{{ $row->Name }}@else{{ $row->Name }}@endif</a></td>
                                    <td>{{ $row->Amount }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Gangwar Kills letzte Woche') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Kills') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($killsLastWeek as $key => $row)
                                <tr @if(auth()->user() && auth()->user()->Id === $row->UserId)class="table-active"@endif>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->UserId != -1)<a href="{{ route('users.show', [$row->UserId]) }}">{{ $row->Name }}@else{{ $row->Name }}@endif</a></td>
                                    <td>{{ $row->Amount }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-2 mt-2">
            <div class="col-12">
                <span class="h2">{{ __('Event: 1.5x Lohn bei den Jobs vom 15.05. 5 Uhr bis 18.05. 5 Uhr') }}</span>
            </div>
        </div>
        @php
            use Illuminate\Support\Facades\DB;
            $total = DB::connection('mysql_logs')->select('SELECT SUM(Earned) AS SumEarned, SUM(Duration) AS SumDuration FROM vrpLogs_Job WHERE ID > ? AND Date BETWEEN ? AND ?;', [1473351, '2020-05-15 05:00:00', '2020-05-18 05:00:00']);
            $top = DB::connection('mysql_logs')->select('SELECT j.UserID, a.Name, SUM(j.Earned) AS SumEarned, SUM(j.Duration) AS SumDuration FROM vrpLogs_Job j INNER JOIN ' . config('database.connections.mysql.database') . '.vrp_account a ON a.Id = j.UserID WHERE j.ID > ? AND j.Date BETWEEN ? AND ? GROUP BY j.UserID ORDER BY SumEarned DESC LIMIT 10;', [1473351, '2020-05-15 05:00:00', '2020-05-18 05:00:00']);
            $topJob = DB::connection('mysql_logs')->select('SELECT Job, SUM(Earned) AS SumEarned, SUM(Duration) AS SumDuration FROM vrpLogs_Job WHERE ID > ? AND Date BETWEEN ? AND ? GROUP BY Job ORDER BY SumEarned DESC LIMIT 10;', [1473351, '2020-05-15 05:00:00', '2020-05-18 05:00:00']);

            if(auth()->user()) {
                $myData = DB::connection('mysql_logs')->select('SELECT SUM(Earned) AS SumEarned, SUM(Duration) AS SumDuration FROM vrpLogs_Job WHERE ID > ? AND UserID = ? AND Date BETWEEN ? AND ?;', [1473351, auth()->user()->Id, '2020-05-15 05:00:00', '2020-05-18 05:00:00'])[0];
                if(!$myData->SumEarned) {
                    $myPos = DB::connection('mysql_logs')->select('SELECT SUM(Earned) AS SumEarned FROM vrpLogs_Job WHERE ID > ? AND Date BETWEEN ? AND ? GROUP BY UserID ORDER BY SumEarned DESC;', [1473351, '2020-05-15 05:00:00', '2020-05-18 05:00:00']);
                } else {
                    $myPos = DB::connection('mysql_logs')->select('SELECT SUM(Earned) AS SumEarned FROM vrpLogs_Job WHERE ID > ? AND Date BETWEEN ? AND ? GROUP BY UserID HAVING SUM(Earned) >= ? ORDER BY SumEarned DESC;', [1473351, '2020-05-15 05:00:00', '2020-05-18 05:00:00', $myData->SumEarned]);
                }
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
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="text-muted small text-uppercase font-weight-bold">{{ __('Dollar verdient') }}</div>
                        <div class="text-value-xl py-3">@money($total[0]->SumEarned)</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="text-muted small text-uppercase font-weight-bold">{{ __('Zeit die mit den Jobs verbracht wurde') }}</div>
                        <div class="text-value-xl py-3">{{ Carbon\Carbon::now()->longAbsoluteDiffForHumans(Carbon\Carbon::now()->addSeconds($total[0]->SumDuration), 5) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 col-md-6">
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
                                <th scope="col">{{ __('Zeit') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($top as $key => $row)
                                <tr @if(auth()->user() && $row->UserID && auth()->user()->Id == $row->UserID)class="table-active"@endif>
                                    <td>{{$key+1}}.</td>
                                    <td>
                                        <a href="{{ route('users.show', [$row->UserID]) }}">{{ $row->Name }}</a>
                                        @if(\App\Http\Controllers\WhoIsOnlineController::isPlayerOnline($row->UserID))
                                            <span class="badge badge-success">online</span>
                                        @else
                                            <span class="badge badge-danger">offline</span>
                                        @endif
                                    </td>
                                    <td>@money($row->SumEarned)</td>
                                    <td>{{ Carbon\Carbon::now()->longAbsoluteDiffForHumans(Carbon\Carbon::now()->addSeconds($row->SumDuration), 3) }}</td>
                                </tr>
                            @endforeach
                            @if(isset($myPos) && $myPos && $myData && count($myPos) > 10)
                                <tr class="table-active">
                                    <td>{{ count($myPos) }}.</td>
                                    <td>
                                        <a href="{{ route('users.show', [auth()->user()->Id]) }}">{{ auth()->user()->Name }}</a>
                                        @if(\App\Http\Controllers\WhoIsOnlineController::isPlayerOnline(auth()->user()->Id))
                                            <span class="badge badge-success">online</span>
                                        @else
                                            <span class="badge badge-danger">offline</span>
                                        @endif
                                    </td>
                                    <td>@money($myData->SumEarned)</td>
                                    <td>{{ Carbon\Carbon::now()->longAbsoluteDiffForHumans(Carbon\Carbon::now()->addSeconds($myData->SumDuration), 3) }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
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
    </div>
@endsection
