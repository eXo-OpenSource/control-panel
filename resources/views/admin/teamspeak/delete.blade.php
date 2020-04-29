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
                                {{ __('Teamspeak Identität löschen') }}
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.teamspeak.destroy', [$teamspeak]) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('DELETE')
                                    <div class="form-group">
                                        <label for="user">{{ __('Benutzer') }}</label>
                                        <input type="text" id="user" class="form-control" disabled value="{{ $teamspeak->user->Name }}" >
                                    </div>

                                    <div class="form-group">
                                        <label for="uniqueId">{{ __('Eindeutige ID') }}</label>
                                        <input type="text" id="uniqueId" class="form-control{{ $errors->has('uniqueId') ? ' is-invalid' : '' }}" disabled name="uniqueId" value="{{ $teamspeak->TeamspeakId }}" >
                                        @if ($errors->has('uniqueId'))
                                            <div class="invalid-feedback">{{ $errors->first('uniqueId') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="notice">{{ __('Notiz') }}</label>
                                        <input type="text" id="notice" class="form-control" placeholder="{{ __('Notiz') }}" disabled name="notice" value="{{ $teamspeak->Notice }}" >
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('Typ') }}</label>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="radioUser" name="type" value="1" class="custom-control-input" disabled @if($teamspeak->Type === 1){{ 'checked' }}@endif>
                                            <label class="custom-control-label" for="radioUser">{{ __('Benutzer') }}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="radioMusicBot" name="type" value="2" class="custom-control-input" disabled @if($teamspeak->Type === 2){{ 'checked' }}@endif>
                                            <label class="custom-control-label" for="radioMusicBot">{{ __('Musikbot') }}</label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-danger float-right">{{ __('Löschen') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
