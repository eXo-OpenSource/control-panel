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
    </div>
    <div class="col-lg-6">
        @can('activityTotal', $group)
            <react-chart data-chart="activity:group:{{ $group->Id }}" data-state="true" data-title="{{ __('Aktivität') }}"></react-chart>
        @endcan
        @can('bank', $group)
            <react-chart data-chart="money:group:{{ $group->Id }}" data-state="true" data-title="{{ __('Einnahmen/Ausgaben') }}"></react-chart>
        @endcan
    </div>
</div>
