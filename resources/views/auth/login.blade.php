@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div class="flex flex-wrap justify-center">
            <div class="w-full max-w-sm">
                <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">

                    <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                        {{ __('Anmeldung') }}
                    </div>

                    <form class="w-full p-6" method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="block mb-4">
                            <label class="form-input-label" for="username">Benutzername</label>
                            <input type="text" id="username" class="form-input mt-1 block w-full{{ $errors->has('username') ? ' border-red-500' : '' }}" placeholder="Benutzername" name="username" value="{{ old('username') }}" required>
                            @if ($errors->has('username'))
                                <p class="form-input-error">
                                    {{ $errors->first('username') }}
                                </p>
                            @endif
                        </div>

                        <div class="block mb-4">
                            <label class="form-input-label" for="password">Passwort</label>
                            <input type="password" id="password" class="form-input mt-1 block w-full{{ $errors->has('password') ? ' border-red-500' : '' }}" placeholder="Passwort" name="password" required>
                            @if ($errors->has('password'))
                                <p class="form-input-error">
                                    {{ $errors->first('password') }}
                                </p>
                            @endif
                        </div>

                        <div class="inline-flex items-center mb-4">
                            <input type="checkbox" name="remember" id="remember" class="form-checkbox" {{ old('remember') ? 'checked' : '' }}>
                            <label class="ml-2 text-sm" for="remember">Anmeldung merken</label>
                        </div>

                        <div class="flex flex-wrap items-center">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Absenden') }}
                            </button>

                            <a class="text-sm text-blue-500 hover:text-blue-700 whitespace-no-wrap no-underline ml-auto" href="https://forum.exo-reallife.de/wsc/index.php?lost-password/">
                                {{ __('Passwort vergessen?') }}
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
