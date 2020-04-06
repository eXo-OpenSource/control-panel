@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4 d-flex justify-content-between align-items-start">
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
                        <react-ban-dialog data-id="{{ $user->Id }}" data-name="{{ $user->Name }}"></react-ban-dialog>
                        @if(auth()->user()->Rank >= 5)<react-unban-dialog data-id="{{ $user->Id }}" data-name="{{ $user->Name }}"></react-unban-dialog>@endif
                        <react-kick-dialog data-id="{{ $user->Id }}" data-name="{{ $user->Name }}"></react-kick-dialog>
                        <react-warns-dialog data-id="{{ $user->Id }}" data-name="{{ $user->Name }}"></react-warns-dialog>
                    @endif
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


@if(!View::hasSection('title'))
    @section('title', $user->Name)
@endif
