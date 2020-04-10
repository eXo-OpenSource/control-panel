@can('logs', $group)
    <div class="row">
        <div class="nav-tabs-boxed col-md-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link @if($log === 'group'){{'active'}}@endif" href="{{ route('groups.show.logs', [$group->Id, 'group']) }}">{{ __('Gruppe') }}</a></li>
                <li class="nav-item"><a class="nav-link @if($log === 'money'){{'active'}}@endif" href="{{ route('groups.show.logs', [$group->Id, 'money']) }}">{{ __('Geld') }}</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    @if($log === 'group')
                        @include('groups.partials.logs.group')
                    @elseif($log === 'money')
                        @include('groups.partials.logs.money')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endcan
