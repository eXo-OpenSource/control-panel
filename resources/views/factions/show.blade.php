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
                                <th>{{ __('Aktivität') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($faction->members()->with('user')->orderBy('FactionRank', 'DESC')->get() as $character)
                                <tr>
                                    <td><a href="{{ route('users.show', [$character->Id]) }}">{{ $character->user->Name }}</a></td>
                                    <td>{{ $character->FactionRank }}</td>
                                    <td>{{ number_format($character->getWeekActivity() / 60, 1, ',', ' ') }} h</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">{{ __('Aktivität') }}</div>
                    <div class="card-body">
                        <div class="chart-wrapper">
                            <canvas id="canvas-1"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">{{ __('Logs - Letzten 100 Einträge') }}</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th>Eintrag</th>
                                <th>Datum</th>
                            </tr>
                            @foreach($faction->logs()->orderBy('Timestamp', 'DESC')->limit(100)->with('user')->with('user.user')->get() as $log)
                                <tr>
                                    <td><a href="{{ route('users.show', [$log->UserId]) }}">{{ $log->user->user->Name }}</a> {{ $log->Description }}</td>
                                    <td>{{ Carbon\Carbon::createFromTimestamp($log->Timestamp)->format('d.m.Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

        var data = {!! json_encode($faction->getActivity(true)) !!};

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
@endsection
