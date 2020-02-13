@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4 d-flex justify-content-between align-items-start">
            <div>
                <h3>{{ $user->Name }}</h3>
            </div>
            <div>
                @auth
                    @include('users.partials.admin-modals')
                @endauth
            </div>
        </div>
        <hr>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item"><a class="nav-link @if($page === ''){{'active'}}@endif" href="{{ route('users.show', [$user->Id]) }}">{{ __('Übersicht') }}</a></li>
                    @can('vehicles', $user)<li class="nav-item"><a class="nav-link @if($page === 'vehicles'){{'active'}}@endif" href="{{ route('users.show.page', [$user->Id, 'vehicles']) }}">{{ __('Fahrzeuge') }}</a></li>@endcan
                    @can('history', $user)<li class="nav-item"><a class="nav-link @if($page === 'history'){{'active'}}@endif" href="{{ route('users.show.page', [$user->Id, 'history']) }}">{{ __('Spielerakte') }}</a></li>@endcan
                    @can('logs', $user)<li class="nav-item"><a class="nav-link @if($page === 'logs'){{'active'}}@endif" href="{{ route('users.show.logs', [$user->Id]) }}">{{ __('Logs') }}</a></li>@endcan
                </ul>
                </ul>
                <div class="tab-content pt-4">
                    @if($page === '')
                        @include('users.partials.overview')
                    @elseif($page === 'vehicles')
                        @include('users.partials.vehicles')
                    @elseif($page === 'history')
                        @include('users.partials.history')
                    @elseif($page === 'logs')
                        @include('users.partials.logs')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
