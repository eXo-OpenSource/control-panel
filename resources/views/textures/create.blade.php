@extends('layouts.app')

@section('title', __('Textur hochladen'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                {{ __('Textur hochladen') }}
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('textures.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">{{ __('Name') }}</label>
                                        <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Namen der Textur" name="name" value="{{ old('name') }}" >
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="vehicle">{{ __('Fahrzeugmodell') }}</label>
                                        <select name="vehicle" id="vehicle" class="form-control custom-select {{ $errors->has('vehicle') ? ' is-invalid' : '' }}">
                                            <option value="">{{ __('[Model auswählen]') }}</option>
                                            @foreach($vehicles as $vehicle)
                                                <option value="{{ $vehicle['Id'] }}" @if($vehicle['Id'] == old('vehicle')){{ 'selected' }}@endif>{{ $vehicle['Name'] }} (ID: {{ $vehicle['Id'] }})</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('vehicle'))
                                            <div class="invalid-feedback">{{ $errors->first('vehicle') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('Typ') }}</label>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="radioPrivate" name="type" value="0" class="custom-control-input{{ $errors->has('type') ? ' is-invalid' : '' }}" @if(old('type') === '0'){{ 'checked' }}@endif>
                                            <label class="custom-control-label" for="radioPrivate">{{ __('Privat nur für mich') }}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="radioPublic" name="type" value="1" class="custom-control-input{{ $errors->has('type') ? ' is-invalid' : '' }}" @if(old('type') === '1'){{ 'checked' }}@endif>
                                            <label class="custom-control-label" for="radioPublic">{{ __('Öffentlich') }}</label>
                                        </div>
                                        @if ($errors->has('type'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('type') }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="custom-file mb-3">
                                        <input type="file" class="custom-file-input" id="texture" name="texture">
                                        <label class="custom-file-label{{ $errors->has('texture') ? ' is-invalid' : '' }}" for="texture">{{ __('Textur auswählen') }}</label>
                                        @if ($errors->has('texture'))
                                            <div class="invalid-feedback">{{ $errors->first('texture') }}</div>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-primary float-right">{{ __('Hochladen') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
