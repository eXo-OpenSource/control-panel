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
                                @can('activity', $company)<th>{{ __('Aktivität') }}</th>@endcan
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($company->members()->with('user')->orderBy('CompanyRank', 'DESC')->get() as $character)
                                <tr>
                                    <td><a href="{{ route('users.show', [$character->Id]) }}">{{ $character->user->Name }}</a></td>
                                    <td>{{ $character->CompanyRank }}</td>
                                    @can('activity', $company)<td>{{ number_format($character->getWeekActivity() / 60, 1, ',', ' ') }} h</td>@endcan
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                @can('activityTotal', $company)
                    <react-chart data-chart="company:{{ $company->Id }}" data-state="true" data-title="Aktivität"></react-chart>
                @endcan
                @can('logs', $company)
                <div class="card">
                    <div class="card-header">{{ __('Logs - Letzten 100 Einträge') }}</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th>Eintrag</th>
                                <th>Datum</th>
                            </tr>
                            @foreach($company->logs()->orderBy('Timestamp', 'DESC')->limit(100)->with('user')->with('user.user')->get() as $log)
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