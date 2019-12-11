@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">{{ __('Mitglieder') }}</div>
                    <div class="card-body">
                        <table class="table table-sm table-responsive-sm">
                            <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Rang') }}</th>
                                @can('activity', $group)<th>{{ __('Aktivität') }}</th>@endcan
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($group->members()->with('user')->orderBy('GroupRank', 'DESC')->get() as $character)
                                <tr>
                                    <td><a href="{{ route('users.show', [$character->Id]) }}">{{ $character->user->Name }}</a></td>
                                    <td>{{ $character->GroupRank }}</td>
                                    @can('activity', $group)<td>{{ number_format($character->getWeekActivity() / 60, 1, ',', ' ') }} h</td>@endcan
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                @can('vehicles', $group)
                <div class="row">
                    @foreach($group->vehicles as $vehicle)
                        <div class="col-md-4">
                            <div class="card">
                                <img class="bd-placeholder-img card-img-top" src="https://exo-reallife.de/images/veh/Vehicle_{{ $vehicle->Model }}.jpg">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $vehicle->getName() }}</h5>
                                    <dl class="vehicle-info">
                                        <dt>Kilometerstand</dt>
                                        <dd>{{ number_format($vehicle->Mileage / 1000, 2, ',', ' ') }} km</dd>
                                        <dt>Lackfarbe</dt>
                                        <dd class="d-flex">
                                            <div class="border" style="height: 25px; width: 25px; background-color: {{ $vehicle->getTuningColor(1) }};"></div>
                                            <div class="border" style="height: 25px; width: 25px; background-color: {{ $vehicle->getTuningColor(2) }};"></div>
                                            <div class="border" style="height: 25px; width: 25px; background-color: {{ $vehicle->getTuningColor(3) }};"></div>
                                            <div class="border" style="height: 25px; width: 25px; background-color: {{ $vehicle->getTuningColor(4) }};"></div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endcan
            <div class="col-lg-6">
                @can('activityTotal', $group)
                <div class="card">
                    <div class="card-header">{{ __('Aktivität') }}</div>
                    <div class="card-body">
                        <div class="chart-wrapper">
                            <canvas id="canvas-1"></canvas>
                        </div>
                    </div>
                </div>
                @endcan
                @can('logs', $group)
                <div class="card">
                    <div class="card-header">{{ __('Logs - Letzten 100 Einträge') }}</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th>Eintrag</th>
                                <th>Datum</th>
                            </tr>
                            @foreach($group->logs()->orderBy('Timestamp', 'DESC')->limit(100)->with('user')->with('user.user')->get() as $log)
                                <tr>
                                    <td><a href="{{ route('users.show', [$log->UserId]) }}">{{ $log->user->user->Name }}</a> {{ $log->Description }}</td>
                                    <td>{{ Carbon\Carbon::createFromTimestamp($log->Timestamp)->format('d.m.Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
@endsection

@section('script')
    @can('activityTotal', $group)
    <script>
        var data = {!! json_encode($group->getActivity(true)) !!};

        var lineChart = new Chart($('#canvas-1'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: data.datasets
            },
            options: {
                scales: {
                    yAxes: [
                        {ticks: {beginAtZero: true, suggestedMax: 8}}
                    ]
                },
                responsive: true
            }
        });
    </script>
    @endcan
@endsection
