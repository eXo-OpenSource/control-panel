@can('logs', $faction)
    <div class="row">
        <div class="nav-tabs-boxed col-md-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link @if($log === 'faction'){{'active'}}@endif" href="{{ route('factions.show.logs', [$faction->Id, 'faction']) }}">{{ __('Fraktion') }}</a></li>
                <li class="nav-item"><a class="nav-link @if($log === 'money'){{'active'}}@endif" href="{{ route('factions.show.logs', [$faction->Id, 'money']) }}">{{ __('Geld') }}</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    @if($log === 'faction')
                        @include('factions.partials.logs.faction')
                    @elseif($log === 'money')
                        @include('factions.partials.logs.money')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endcan
