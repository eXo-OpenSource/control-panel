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
                        @can('activity', $faction)<th>{{ __('Aktivität') }}</th>@endcan
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($faction->members()->with('user')->orderBy('FactionRank', 'DESC')->get() as $character)
                        <tr>
                            <td>@if($character->user)<a href="{{ route('users.show', [$character->Id]) }}">{{ $character->user->Name }}</a>@else{{ 'Unknown' }}@endif</td>
                            <td>{{ $character->FactionRank }}</td>
                            @can('activity', $faction)<td>{{ number_format($character->getWeekActivity() / 60, 1, ',', ' ') }} h</td>@endcan
                            <td>
                                @if($character->user->isOnline())
                                    <span class="badge badge-success">online</span>
                                @else
                                    <span class="badge badge-danger">offline</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        @can('activityTotal', $faction)
            <react-chart data-chart="activity:faction:{{ $faction->Id }}" data-state="true" data-title="{{ __('Aktivität') }}"></react-chart>
        @endcan
        @can('bank', $faction)
            <react-chart data-chart="money:faction:{{ $faction->Id }}" data-state="true" data-title="{{ __('Einnahmen/Ausgaben') }}"></react-chart>
        @endcan
    </div>
</div>
