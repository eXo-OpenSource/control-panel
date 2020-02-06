@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h3>{{ $group->Name }}</h3>
        <hr>
        <div class="row justify-content-center">
            <div class="col-md-12">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item"><a class="nav-link @if($page === ''){{'active'}}@endif" href="{{ route('groups.show', [$group->Id]) }}">{{ __('Übersicht') }}</a></li>
                        @can('vehicles', $group)<li class="nav-item"><a class="nav-link @if($page === 'vehicles'){{'active'}}@endif" href="{{ route('groups.show.page', [$group->Id, 'vehicles']) }}">{{ __('Fahrzeuge') }}</a></li>@endcan
                        @can('logs', $group)<li class="nav-item"><a class="nav-link @if($page === 'logs'){{'active'}}@endif" href="{{ route('groups.show.page', [$group->Id, 'logs']) }}">{{ __('Logs') }}</a></li>@endcan
                    </ul>
                </ul>
                <div class="tab-content pt-4">
                    @if($page === '')
                        @include('groups.partials.overview')
                    @elseif($page === 'vehicles')
                        @include('groups.partials.vehicles')
                    @elseif($page === 'logs')
                        @include('groups.partials.logs')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

