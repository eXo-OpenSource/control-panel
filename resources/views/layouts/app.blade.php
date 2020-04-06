<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @if(View::hasSection('title'))
        <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>
    @else
        <title>{{ config('app.name', 'Laravel') }}</title>
    @endif

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @yield('head')
</head>
<body class="c-app c-dark-theme">
    <!-- // @include('layouts.partials.header')-->

    @include('layouts.partials.sidebar')

    <div id="app" class="c-wrapper">
        <header class="c-header c-header-light c-header-fixed"> <!--c-header-with-subheader -->
            <button class="c-header-toggler c-class-toggler d-lg-none mr-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show"><span class="c-header-toggler-icon"></span></button><a class="c-header-brand d-sm-none" href="#"><img class="c-header-brand" src="/images/logo.png" alt="eXo Logo"></a>
            <button class="c-header-toggler c-class-toggler ml-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true"><span class="c-header-toggler-icon"></span></button>

            @yield('top-menu')

            <ul class="c-header-nav ml-auto">

                @guest
                    <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                @else
                    <li class="c-header-nav-item dropdown px-3">
                        <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <div class="c-avatar"><img class="c-avatar-img" src="/images/skins/head/{{ Auth::user()->character->Skin }}.png" alt="{{ Auth::user()->Name }}"></div>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right pt-0">
                            <div class="dropdown-header bg-light py-2"><strong>Account</strong></div>
                            <a class="dropdown-item" href="{{ route('users.show', ['user' => auth()->user()]) }}">
                                {{ __('Character') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
            <!--
            <div class="c-subheader px-3">

                <ol class="breadcrumb border-0 m-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>

                </ol>
            </div>-->
        </header>

        <div class="c-body">
            <main class="c-main">
                <div class="container">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}" role="alert">
                                {{ Session::get('alert-' . $msg) }}
                            </div>
                        @endif
                    @endforeach
                </div>

                @yield('content')
            </main>
        </div>

        <footer class="c-footer">
            <div>@include('layouts.partials.online')</div>
            <div class="mfs-auto">Release: {{ substr(config('sentry.release'), 0, 7) }}</div>
        </footer>
    </div>

    <!-- Scripts -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.1/dist/Chart.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/umd/popper.min.js" integrity="sha384-L2pyEeut/H3mtgCBaUNw7KWzp5n9&#43;4pDQiExs933/5QfaTh8YStYFFkOzSoXjlTb" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/umd/popper.min.js" integrity="sha384-L2pyEeut/H3mtgCBaUNw7KWzp5n9&#43;4pDQiExs933/5QfaTh8YStYFFkOzSoXjlTb" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@coreui/coreui@3.0.0-rc.0/dist/js/coreui.min.js"></script>
    <script src="{{ mix('js/app.js') }}" defer></script>
    @if(env('SENTRY_JS_DSN') && !env('APP_DEBUG'))
    <script src="https://browser.sentry-cdn.com/5.12.1/bundle.min.js" integrity="sha384-y+an4eARFKvjzOivf/Z7JtMJhaN6b+lLQ5oFbBbUwZNNVir39cYtkjW1r6Xjbxg3" crossorigin="anonymous"></script>
    <script>
        Sentry.init({ dsn: '{{ env('SENTRY_JS_DSN') }}' });
    </script>
    @endif
    @yield('script')
</body>
</html>
