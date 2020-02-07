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
                            <td>@if($character->user)<a href="{{ route('users.show', [$character->Id]) }}">{{ $character->user->Name }}</a>@else{{ 'Unknown' }}@endif</td>
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
            <react-chart data-chart="company:{{ $company->Id }}" data-state="true" data-title="{{ __('Aktivität') }}"></react-chart>
        @endcan
    </div>
</div>
