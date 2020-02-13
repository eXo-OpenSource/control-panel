@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="nav-tabs-boxed col-md-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link @if($log === 'kills'){{'active'}}@endif" href="{{ route('admin.logs.show', ['kills']) }}">{{ __('Morde') }}</a></li>
                    <li class="nav-item"><a class="nav-link @if($log === 'damage'){{'active'}}@endif" href="{{ route('admin.logs.show', ['damage']) }}">{{ __('Schaden') }}</a></li>
                    <li class="nav-item"><a class="nav-link @if($log === 'heal'){{'active'}}@endif" href="{{ route('admin.logs.show', ['heal']) }}">{{ __('Heilung') }}</a></li>
                    <li class="nav-item"><a class="nav-link @if($log === 'action'){{'active'}}@endif" href="{{ route('admin.logs.show', ['action']) }}">{{ __('Aktionen') }}</a></li>
                    <li class="nav-item"><a class="nav-link @if($log === 'login'){{'active'}}@endif" href="{{ route('admin.logs.show', ['login']) }}">{{ __('Logins') }}</a></li>
                    <li class="nav-item"><a class="nav-link @if($log === 'arrest'){{'active'}}@endif" href="{{ route('admin.logs.show', ['arrest']) }}">{{ __('Arrests') }}</a></li>
                    <li class="nav-item"><a class="nav-link @if($log === 'ammunation'){{'active'}}@endif" href="{{ route('admin.logs.show', ['ammunation']) }}">{{ __('Ammunation') }}</a></li>
                    <li class="nav-item"><a class="nav-link @if($log === 'chat'){{'active'}}@endif" href="{{ route('admin.logs.show', ['chat']) }}">{{ __('Chat') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        @if($log === 'kills')
                            @include('admin.logs.partials.kills')
                        @elseif($log === 'damage')
                            @include('admin.logs.partials.damage')
                        @elseif($log === 'heal')
                            @include('admin.logs.partials.heal')
                        @elseif($log === 'action')
                            @include('admin.logs.partials.action')
                        @elseif($log === 'login')
                            @include('admin.logs.partials.login')
                        @elseif($log === 'arrest')
                            @include('admin.logs.partials.arrest')
                        @elseif($log === 'ammunation')
                            @include('admin.logs.partials.ammunation')
                        @elseif($log === 'chat')
                            @include('admin.logs.partials.chat')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection