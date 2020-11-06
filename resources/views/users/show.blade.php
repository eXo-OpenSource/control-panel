@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-start">
                <div>
                    <p class="h3">
                        {{ $user->Name }}
                        @if($user->isOnline())
                            <span class="badge badge-success">online</span>
                        @else
                            <span class="badge badge-danger">offline</span>
                        @endif
                    </p>
                </div>
                <div>
                    @auth
                        @if(auth()->user()->Rank >= 3)
                            <a class="btn btn-primary" href="{{ 'https://exo-reallife.de/index.php?page=admin&f=spieler&id=' . $user->Id }}">{{ __('Altes CP') }}</a>

                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('MTA Server') }}
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.users.screenshots.store', [$user->Id]) }}" onclick="event.preventDefault(); document.getElementById('screenshot-form').submit();">{{ __('Screenshot anfordern') }}</a>
                                    <form id="screenshot-form" action="{{ route('admin.users.screenshots.store', [$user->Id]) }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                    <a class="dropdown-item" href="{{ route('admin.users.screenshots.index', [$user->Id]) }}">{{ __('Screenshots') }}</a>
                                    <react-ban-dialog data-id="{{ $user->Id }}" data-name="{{ $user->Name }}"></react-ban-dialog>
                                    @if(auth()->user()->Rank >= 5)<react-unban-dialog data-id="{{ $user->Id }}" data-name="{{ $user->Name }}"></react-unban-dialog>@endif
                                    <react-prison-dialog data-id="{{ $user->Id }}" data-name="{{ $user->Name }}"></react-prison-dialog>
                                    <react-unprison-dialog data-id="{{ $user->Id }}" data-name="{{ $user->Name }}"></react-unprison-dialog>
                                    <react-kick-dialog data-id="{{ $user->Id }}" data-name="{{ $user->Name }}"></react-kick-dialog>
                                    <react-warns-dialog data-id="{{ $user->Id }}" data-name="{{ $user->Name }}"></react-warns-dialog>
                                </div>
                            </div>

                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ 'TeamSpeak' }}
                                </button>
                                <div class="dropdown-menu">
                                    <react-team-speak-ban-dialog data-id="{{ $user->Id }}" data-name="{{ $user->Name }}"></react-team-speak-ban-dialog>
                                    @if(auth()->user()->Rank >= 5)<react-team-speak-unban-dialog data-id="{{ $user->Id }}" data-name="{{ $user->Name }}"></react-team-speak-unban-dialog>@endif
                                </div>
                            </div>

                            @if(auth()->user()->Id !== $user->Id && !auth()->user()->isImpersonated() && in_array(auth()->user()->Id, explode(',', env('IMPERSONATE_PERMISSION', ''))))
                                <a class="btn btn-dark" href="{{ route('admin.users.impersonate.start', [$user->Id]) }}" onclick="event.preventDefault(); document.getElementById('impersonate-form').submit();">{{ __('Impersonate') }}</a>
                                <form id="impersonate-form" action="{{ route('admin.users.impersonate.start', [$user->Id]) }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>
        </div>
        <hr>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item"><a class="nav-link @if($page === ''){{'active'}}@endif" href="{{ route('users.show', [$user->Id]) }}">{{ __('Übersicht') }}</a></li>
                    @can('vehicles', $user)<li class="nav-item"><a class="nav-link @if($page === 'vehicles'){{'active'}}@endif" href="{{ route('users.show.page', [$user->Id, 'vehicles']) }}">{{ __('Fahrzeuge') }}</a></li>@endcan
                    @can('teamspeak', $user)<li class="nav-item"><a class="nav-link @if($page === 'teamspeak'){{'active'}}@endif" href="{{ route('users.show.page', [$user->Id, 'teamspeak']) }}">{{ __('TeamSpeak') }}</a></li>@endcan
                    @can('trainings', $user)<li class="nav-item"><a class="nav-link @if($page === 'trainings'){{'active'}}@endif" href="{{ route('users.show.page', [$user->Id, 'trainings']) }}">{{ __('Schulungen') }}</a></li>@endcan
                    @can('history', $user)<li class="nav-item"><a class="nav-link @if($page === 'history'){{'active'}}@endif" href="{{ route('users.show.page', [$user->Id, 'history']) }}">{{ __('Spielerakte') }}</a></li>@endcan
                    @can('mods', $user)<li class="nav-item"><a class="nav-link @if($page === 'mods'){{'active'}}@endif" href="{{ route('users.show.page', [$user->Id, 'mods']) }}">{{ __('Mods') }}</a></li>@endcan
                    @can('hardware', $user)<li class="nav-item"><a class="nav-link @if($page === 'hardware'){{'active'}}@endif" href="{{ route('users.show.page', [$user->Id, 'hardware']) }}">{{ __('Hardware') }}</a></li>@endcan
                    @can('statistics', $user)<li class="nav-item"><a class="nav-link @if($page === 'statistics'){{'active'}}@endif" href="{{ route('users.show.page', [$user->Id, 'statistics']) }}">{{ __('Statistiken') }}</a></li>@endcan
                    @can('logs', $user)<li class="nav-item"><a class="nav-link @if($page === 'logs'){{'active'}}@endif" href="{{ route('users.show.logs', [$user->Id]) }}">{{ __('Logs') }}</a></li>@endcan
                </ul>
                </ul>
                <div class="tab-content pt-4">
                    @if($page === '')
                        @include('users.partials.overview')
                    @elseif($page === 'vehicles')
                        @include('users.partials.vehicles')
                    @elseif($page === 'teamspeak')
                        @include('users.partials.teamspeak')
                    @elseif($page === 'trainings')
                        @include('users.partials.trainings')
                    @elseif($page === 'history')
                        @include('users.partials.history')
                    @elseif($page === 'logs')
                        @include('users.partials.logs')
                    @elseif($page === 'mods')
                        @include('users.partials.mods')
                    @elseif($page === 'hardware')
                        @include('users.partials.hardware')
                    @elseif($page === 'statistics')
                        @include('users.partials.statistics')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection


@if(!View::hasSection('title'))
    @section('title', $user->Name)
@endif
