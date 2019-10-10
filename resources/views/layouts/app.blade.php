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
<body class="bg-gray-100 h-screen antialiased leading-none">
    <div id="app">
        <nav class="bg-blue-900 shadow mb-8 py-6">
            <div class="container mx-auto">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="flex items-center flex-no-shrink text-white mr-6 ml-4 sm:ml-0">
                        <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100 no-underline">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>
                    <div class="block sm:hidden">
                        <button @click="toggle" class="flex items-center px-3 py-2 border rounded text-gray-300 border-gray-300 hover:text-white hover:border-white">
                            <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                        </button>
                    </div>
                    <div :class="open ? 'block': 'hidden'" class="w-full flex-grow sm:flex sm:items-center sm:w-auto">
                        <div class="text-sm sm:flex-grow">
                            <a class="no-underline hover:text-white block sm:inline-block text-gray-300 text-sm p-3" href="{{ route('factions.index') }}">Fraktion</a>
                            <a class="no-underline hover:text-white block sm:inline-block text-gray-300 text-sm p-3" href="{{ route('companies.index') }}">Unternehmen</a>
                            <a class="no-underline hover:text-white block sm:inline-block text-gray-300 text-sm p-3" href="{{ route('groups.index') }}">Gruppen</a>
                            <a class="no-underline hover:text-white block sm:inline-block text-gray-300 text-sm p-3" href="{{ route('textures.index') }}">Texturen</a>
                            @auth
                                @if(auth()->user()->Rank >= 3)
                                    <a class="no-underline hover:text-white block sm:inline-block text-gray-300 text-sm p-3" href="{{ route('admin.dashboard.index') }}">Admin</a>
                                @endif
                            @endauth
                        </div>
                        <div>
                            @guest
                                <a class="no-underline hover:text-white block sm:inline-block text-gray-300 text-sm p-3" href="{{ route('login') }}">{{ __('Login') }}</a>
                            @else
                                <a class="no-underline hover:text-white block sm:inline-block text-gray-300 text-sm p-3" href="{{ route('users.show', ['user' => auth()->user()]) }}">{{ Auth::user()->Name }}</a>

                                <a href="{{ route('logout') }}"
                                   class="no-underline hover:text-white block sm:inline-block text-gray-300 text-sm p-3"
                                   onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    {{ csrf_field() }}
                                </form>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex items-center">
            <div class="md:w-2/3 md:mx-auto">


                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                    @endif
                @endforeach
            </div>
        </div>
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
