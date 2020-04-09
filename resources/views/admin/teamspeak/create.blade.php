@extends('layouts.app')

@section('title', __('Teamspeak'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                {{ __('Teamspeak Identität verknüpfen') }}
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.users.teamspeak.store', [$user]) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="user">{{ __('Benutzer') }}</label>
                                        <input type="text" id="user" class="form-control" disabled value="{{ $user->Name }}" >
                                    </div>

                                    <div class="form-group">
                                        <label for="uniqueId">{{ __('Eindeutige ID') }}</label>
                                        <input type="text" id="uniqueId" class="form-control{{ $errors->has('uniqueId') ? ' is-invalid' : '' }}" placeholder="{{ __('Eindeutige ID') }}" name="uniqueId" value="{{ old('uniqueId') }}" >
                                        @if ($errors->has('uniqueId'))
                                            <div class="invalid-feedback">{{ $errors->first('uniqueId') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="notice">{{ __('Notiz') }}</label>
                                        <input type="text" id="notice" class="form-control{{ $errors->has('notice') ? ' is-invalid' : '' }}" placeholder="{{ __('Notiz') }}" name="notice" value="{{ old('notice') }}" >
                                        @if ($errors->has('notice'))
                                            <div class="invalid-feedback">{{ $errors->first('notice') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('Typ') }}</label>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="radioUser" name="type" value="1" class="custom-control-input{{ $errors->has('type') ? ' is-invalid' : '' }}" @if(old('type') === '0'){{ 'checked' }}@endif>
                                            <label class="custom-control-label" for="radioUser">{{ __('Benutzer') }}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="radioMusicBot" name="type" value="2" class="custom-control-input{{ $errors->has('type') ? ' is-invalid' : '' }}" @if(old('type') === '1'){{ 'checked' }}@endif>
                                            <label class="custom-control-label" for="radioMusicBot">{{ __('Musikbot') }}</label>
                                        </div>
                                        @if ($errors->has('type'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('type') }}
                                            </div>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-primary float-right">{{ __('Speichern') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
