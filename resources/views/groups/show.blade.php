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
                                    <td>@if($character->user)<a href="{{ route('users.show', [$character->Id]) }}">{{ $character->user->Name }}</a>@else{{ 'Unknown' }}@endif</td>
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
                            @include('partials.vehicle')
                        </div>
                    @endforeach
                </div>
                @endcan
            </div>
            <div class="col-lg-6">
                @can('activityTotal', $group)
                    <react-chart data-chart="group:{{ $group->Id }}" data-state="true" data-title="{{ __('Aktivität') }}"></react-chart>
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
                                    <td>@if($log->user and $log->user->user)<a href="{{ route('users.show', [$log->user->user->Id]) }}">{{ $log->user->user->Name }}</a>@else{{ 'Unknown' }}@endif {{ $log->Description }}</td>
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

