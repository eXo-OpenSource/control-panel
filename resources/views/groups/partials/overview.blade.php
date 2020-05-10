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
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($group->members()->with('user')->orderBy('GroupRank', 'DESC')->get() as $character)
                        <tr>
                            <td>@if($character->user)<a href="{{ route('users.show', [$character->Id]) }}">{{ $character->user->Name }}</a>@else{{ 'Unknown' }} ({{ $character->Id }})@endif</td>
                            <td>{{ $character->GroupRank }}</td>
                            @can('activity', $group)<td>{{ number_format($character->getWeekActivity() / 60, 1, ',', ' ') }} h</td>@endcan
                            <td>
                                @if($character->user && $character->user->isOnline())
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
        @can('bank', $group)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">{{ __('Details') }}</div>
                        <div class="card-body">
                            <td>{{ __('Bank') }}</td>
                            <dd>{{ number_format($group->bank->Money, 0, ',', ' ') }}$</dd>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('activityTotal', $group)
            <div class="row">
                <div class="col-12">
                    <react-chart data-chart="activity:group:{{ $group->Id }}" data-state="true" data-title="{{ __('Aktivität') }}"></react-chart>
                </div>
            </div>
        @endcan
        @can('bank', $group)
            <div class="row">
                <div class="col-12">
                    <react-chart data-chart="money:group:{{ $group->Id }}" data-state="true" data-title="{{ __('Einnahmen/Ausgaben') }}"></react-chart>
                </div>
            </div>
        @endcan
    </div>
</div>
