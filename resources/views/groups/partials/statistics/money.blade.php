<div class="row mb-2">
    <div class="col-12">
        <span class="h3">{{ __('Details Chart') }}</span>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <react-chart data-chart="money:group:{{ $group->Id }}:both" data-state="true" data-height="80vh" data-date="{{ Carbon\Carbon::now()->format('Y-m-d') }}" data-title="{{ __('Einnahmen/Ausgaben') }}"></react-chart>
    </div>
    <div class="col-6">
    </div>
</div>
<div class="row mb-2">
    <div class="col-12">
        <span class="h3">{{ Carbon\Carbon::now()->format('Y-m-d') }}</span>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <react-chart data-chart="money:group:{{ $group->Id }}:in" data-state="true" data-date="{{ Carbon\Carbon::now()->format('Y-m-d') }}" data-title="{{ __('Einnahmen') }}"></react-chart>
    </div>
    <div class="col-6">
        <react-chart data-chart="money:group:{{ $group->Id }}:out" data-state="true" data-date="{{ Carbon\Carbon::now()->format('Y-m-d') }}" data-title="{{ __('Ausgaben') }}"></react-chart>
    </div>
</div>
<div class="row mb-2">
    <div class="col-12">
        <span class="h3">{{ Carbon\Carbon::now()->subDay()->format('Y-m-d') }}</span>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <react-chart data-chart="money:group:{{ $group->Id }}:in" data-state="true" data-date="{{ Carbon\Carbon::now()->subDay()->format('Y-m-d') }}" data-title="{{ __('Einnahmen') }}"></react-chart>
    </div>
    <div class="col-6">
        <react-chart data-chart="money:group:{{ $group->Id }}:out" data-state="true" data-date="{{ Carbon\Carbon::now()->subDay()->format('Y-m-d') }}" data-title="{{ __('Ausgaben') }}"></react-chart>
    </div>
</div>
