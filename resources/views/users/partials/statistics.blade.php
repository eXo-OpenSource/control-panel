@can('statistics', $user)
    <div class="row">
        <div class="nav-tabs-boxed col-md-12">
            <ul class="nav nav-tabs nav-pills mb-4" role="tablist">
                <li class="nav-item"><a class="nav-link @if($statistic === 'money'){{'active'}}@endif" href="{{ route('users.show.statistics', [$user->Id, 'money']) }}">{{ __('Geld') }}</a></li>
            </ul>

            @if($statistic === 'money')
                @include('users.partials.statistics.money')
            @endif
        </div>
    </div>
@endcan
