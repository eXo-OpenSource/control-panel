@extends('layouts.app')

@section('title', $company->Name)

@section('content')
    <div class="container-fluid">
        <h3>{{ $company->Name }}</h3>
        <hr>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item"><a class="nav-link @if($page === ''){{'active'}}@endif" href="{{ route('companies.show', [$company->Id]) }}">{{ __('Übersicht') }}</a></li>
                    @can('vehicles', $company)<li class="nav-item"><a class="nav-link @if($page === 'vehicles'){{'active'}}@endif" href="{{ route('companies.show.page', [$company->Id, 'vehicles']) }}">{{ __('Fahrzeuge') }}</a></li>@endcan
                    @can('statistics', $company)<li class="nav-item"><a class="nav-link @if($page === 'statistics'){{'active'}}@endif" href="{{ route('companies.show.page', [$company->Id, 'statistics']) }}">{{ __('Statistiken') }}</a></li>@endcan
                    @can('logs', $company)<li class="nav-item"><a class="nav-link @if($page === 'logs'){{'active'}}@endif" href="{{ route('companies.show.page', [$company->Id, 'logs']) }}">{{ __('Logs') }}</a></li>@endcan
                </ul>
                <div class="tab-content pt-4">
                    @if($page === '')
                        @include('companies.partials.overview')
                    @elseif($page === 'vehicles')
                        @include('companies.partials.vehicles')
                    @elseif($page === 'logs')
                        @include('companies.partials.logs')
                    @elseif($page === 'statistics')
                        @include('companies.partials.statistics')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
