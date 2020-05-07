@extends('layouts.app')

@section('title', $faction->Name)

@section('content')
    <div class="container-fluid">
        <h3>{{ $faction->Name }}</h3>
        <hr>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item"><a class="nav-link @if($page === ''){{'active'}}@endif" href="{{ route('factions.show', [$faction->Id]) }}">{{ __('Übersicht') }}</a></li>
                    @can('vehicles', $faction)<li class="nav-item"><a class="nav-link @if($page === 'vehicles'){{'active'}}@endif" href="{{ route('factions.show.page', [$faction->Id, 'vehicles']) }}">{{ __('Fahrzeuge') }}</a></li>@endcan
                    @can('statistics', $faction)<li class="nav-item"><a class="nav-link @if($page === 'statistics'){{'active'}}@endif" href="{{ route('factions.show.page', [$faction->Id, 'statistics']) }}">{{ __('Statistken') }}</a></li>@endcan
                    @can('logs', $faction)<li class="nav-item"><a class="nav-link @if($page === 'logs'){{'active'}}@endif" href="{{ route('factions.show.page', [$faction->Id, 'logs']) }}">{{ __('Logs') }}</a></li>@endcan
                </ul>
                <div class="tab-content pt-4">
                    @if($page === '')
                        @include('factions.partials.overview')
                    @elseif($page === 'vehicles')
                        @include('factions.partials.vehicles')
                    @elseif($page === 'logs')
                        @include('factions.partials.logs')
                    @elseif($page === 'statistics')
                        @include('factions.partials.statistics')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
