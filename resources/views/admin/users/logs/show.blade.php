@extends('layouts.app')

@section('top-menu')
    <ul class="c-header-nav d-md-down-none">
        <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{ route('admin.users.logs.show', [$user->Id, 'punish']) }}">{{ __('Logs') }}</a></li>
    </ul>
@endsection

@section('content')

    <div class="container-fluid">
        <h3><a href="{{ route('users.show', [$user->Id]) }}">{{ $user->Name }}</a></h3>
        <hr>
        <div class="row justify-content-center">
            <div class="nav-tabs-boxed col-md-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link @if($log === 'punish'){{'active'}}@endif" href="{{ route('admin.users.logs.show', [$user->Id, 'punish']) }}">Strafen</a></li>
                    <li class="nav-item"><a class="nav-link @if($log === 'kills'){{'active'}}@endif" href="{{ route('admin.users.logs.show', [$user->Id, 'kills']) }}">Morde</a></li>
                    <li class="nav-item"><a class="nav-link @if($log === 'deaths'){{'active'}}@endif" href="{{ route('admin.users.logs.show', [$user->Id, 'deaths']) }}">Tode</a></li>
                    <li class="nav-item"><a class="nav-link @if($log === 'heal'){{'active'}}@endif" href="{{ route('admin.users.logs.show', [$user->Id, 'heal']) }}">Heilung</a></li>
                    <li class="nav-item"><a class="nav-link @if($log === 'damage'){{'active'}}@endif" href="{{ route('admin.users.logs.show', [$user->Id, 'damage']) }}">Schaden</a></li>
                    <li class="nav-item"><a class="nav-link @if($log === 'money'){{'active'}}@endif" href="{{ route('admin.users.logs.show', [$user->Id, 'money']) }}">Geld</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="punish">
                        @if($log === 'punish')
                            @include('admin.users.logs.partials.punish')
                        @elseif($log === 'kills')
                            @include('admin.users.logs.partials.kills')
                        @elseif($log === 'deaths')
                            @include('admin.users.logs.partials.deaths')
                        @elseif($log === 'heal')
                            @include('admin.users.logs.partials.heal')
                        @elseif($log === 'damage')
                            @include('admin.users.logs.partials.damage')
                        @elseif($log === 'money')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
