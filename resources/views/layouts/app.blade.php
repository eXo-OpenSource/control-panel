<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="c-app c-dark-theme pace-done pace-done">
    <div id="app" class="c-wrapper">
        <header class="c-header c-header-light c-header-fixed px-5"> <!--c-header-with-subheader -->
            <ul class="c-header-nav d-md-down-none">
                <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a></li>
                <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{ route('factions.index') }}">Fraktion</a></li>
                <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{ route('companies.index') }}">Unternehmen</a></li>
                <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{ route('groups.index') }}">Gruppen</a></li>
                <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{ route('textures.index') }}">Texturen</a></li>
                @auth
                    @if(auth()->user()->Rank >= 3)
                        <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{ route('admin.dashboard.index') }}">Admin</a></li>
                    @endif
                @endauth
            </ul>
            <ul class="c-header-nav ml-auto">

                @guest
                    <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                @else
                    <li class="c-header-nav-item dropdown px-3"><a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <div><span>{{ Auth::user()->Name }}</span></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pt-0">
                            <div class="dropdown-header bg-light py-2"><strong>Account</strong></div>
                            <a class="dropdown-item" href="{{ route('users.show', ['user' => auth()->user()]) }}">
                                {{ __('Character') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('teamspeak.index') }}">
                                {{ __('Teamspeak') }}
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

        <div class="flex items-center">
            <div class="md:w-2/3 md:mx-auto">


                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="c-body">
            <main class="c-main">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.1/dist/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/umd/popper.min.js" integrity="sha384-L2pyEeut/H3mtgCBaUNw7KWzp5n9&#43;4pDQiExs933/5QfaTh8YStYFFkOzSoXjlTb" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@coreui/coreui@3.0.0-alpha.13/dist/js/coreui.min.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('script')
</body>
</html>
