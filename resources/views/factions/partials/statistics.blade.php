@can('statistics', $faction)
    <div class="row">
        <div class="nav-tabs-boxed col-md-12">
            <ul class="nav nav-tabs nav-pills mb-4" role="tablist">
                <li class="nav-item"><a class="nav-link @if($statistic === 'money'){{'active'}}@endif" href="{{ route('factions.show.statistics', [$faction->Id, 'money']) }}">{{ __('Geld') }}</a></li>
            </ul>

            @if($statistic === 'money')
                @include('factions.partials.statistics.money')
            @endif
        </div>
    </div>
@endcan
