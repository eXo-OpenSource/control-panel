@can('logs', $company)
    <div class="row">
        <div class="nav-tabs-boxed col-md-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link @if($log === 'company'){{'active'}}@endif" href="{{ route('companies.show.logs', [$company->Id, 'company']) }}">{{ __('Unternehmen') }}</a></li>
                <li class="nav-item"><a class="nav-link @if($log === 'money'){{'active'}}@endif" href="{{ route('companies.show.logs', [$company->Id, 'money']) }}">{{ __('Geld') }}</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    @if($log === 'company')
                        @include('companies.partials.logs.company')
                    @elseif($log === 'money')
                        @include('companies.partials.logs.money')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endcan
