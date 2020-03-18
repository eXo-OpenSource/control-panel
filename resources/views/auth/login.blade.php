@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card-group">
                    <div class="card p-4">
                        <div class="card-body">
                            <h1>{{ __('Anmeldung') }}</h1>
                            <p class="text-muted"></p>
                            <form class="@if($errors->count() > 0){{ 'was-validated_2' }}@endif" method="POST" action="{{ route('login') }}" novalidate>
                                @csrf

                                <div class="mb-3">
                                    <label for="username">{{ __('Benutzername') }}</label>
                                    <div class="input-group is-invalid">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        </div>
                                        <input id="username" name="username" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" type="text" placeholder="{{ __('Benutzername') }}" value="{{ old('username') }}" required>
                                        @if ($errors->has('username'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('username') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password">{{ __('Passwort') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        </div>
                                        <input id="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" placeholder="{{ __('Passwort') }}" value="{{ old('password') }}" required>
                                        @if ($errors->has('password'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('password') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Anmeldung merken') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary px-4">
                                            {{ __('Absenden') }}
                                        </button>
                                    </div>
                                    <div class="col-6 text-right">
                                        <a class="btn btn-link px-0" href="https://forum.exo-reallife.de/wsc/index.php?lost-password/">
                                            {{ __('Passwort vergessen?') }}
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
