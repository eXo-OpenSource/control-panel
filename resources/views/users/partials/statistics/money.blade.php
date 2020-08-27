<div class="row mb-2">
    <div class="col-12">
        <span class="h3">{{ __('Details Chart') }}</span>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <react-chart data-chart="money:user:{{ $user->Id }}:both" data-state="true" data-height="80vh" data-date="{{ Carbon\Carbon::now()->format('Y-m-d') }}" data-title="{{ __('Einnahmen/Ausgaben') }}"></react-chart>
    </div>
    <div class="col-6">
    </div>
</div>
