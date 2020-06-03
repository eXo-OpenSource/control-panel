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
    @if(!request()->exists('minimal'))
        @include('layouts.partials.sidebar')
    @endif

    <div id="app" class="c-wrapper">
        @if(!request()->exists('minimal'))
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
                                @if(auth()->user()->isImpersonated())
                                    <a class="dropdown-item" href="{{ route('admin.users.impersonate.stop', [auth()->user()->Id]) }}" onclick="event.preventDefault(); document.getElementById('stop-impersonation-form').submit();">{{ __('Stop impersonation') }}</a>
                                    <form id="stop-impersonation-form" action="{{ route('admin.users.impersonate.stop', [auth()->user()->Id]) }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                @else
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                @endif
                            </div>
                        </li>
                    @endguest
                </ul>
            </header>
        @endif

        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}" role="alert">
                                        {{ Session::get('alert-' . $msg) }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                @yield('content')
            </main>
        </div>

        @if(!request()->exists('minimal'))
            <footer class="c-footer">
                <div>@include('layouts.partials.online')</div>
                <div class="mfs-auto">{{ __('Release: :version', ['version' => substr(config('sentry.release'), 0, 7)]) }}</div>
            </footer>
        @endif
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/popper.min.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script>
        @if(auth()->user())
            window.Exo = {
                UserId: {{ auth()->user()->Id }},
                UserName: '{{ auth()->user()->Name }}',
                Rank: {{ auth()->user()->Rank }},
                Env: '{{ env('APP_ENV') }}',
            };
        @else
            window.Exo = {
                Env: '{{ env('APP_ENV') }}',
            };
        @endif
    </script>
    @if(env('SENTRY_JS_DSN') && !env('APP_DEBUG'))
    <script>
        SentryMin.init({ dsn: '{{ env('SENTRY_JS_DSN') }}' });
        if(Exo.UserId) {
            SentryMin.setUser({id: Exo.UserId, username: Exo.UserName});
        }
    </script>
    @endif
    @yield('script')
</body>
</html>
