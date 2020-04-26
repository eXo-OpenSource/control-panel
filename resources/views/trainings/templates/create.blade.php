@extends('layouts.app')

@section('title', __('Vorlage anlegen') . ' - ' . __('Vorlagen') .' - ' . __('Schulungen'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Vorlage anlegen') }}
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('trainings.templates.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if(count($targets) > 1)

                                <div class="form-group">
                                    <label>{{ __('Typ') }}</label>
                                    @if(in_array('faction', $targets))
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="radioFaction" name="type" value="faction" class="custom-control-input{{ $errors->has('type') ? ' is-invalid' : '' }}" @if(old('type') === 'faction'){{ 'checked' }}@endif>
                                            <label class="custom-control-label" for="radioFaction">{{ __('Fraktion') }}</label>
                                        </div>
                                    @endif
                                    @if(in_array('company', $targets))
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="radioCompany" name="type" value="company" class="custom-control-input{{ $errors->has('type') ? ' is-invalid' : '' }}" @if(old('type') === 'company'){{ 'checked' }}@endif>
                                            <label class="custom-control-label" for="radioCompany">{{ __('Unternehmen') }}</label>
                                        </div>
                                    @endif
                                    @if ($errors->has('type'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('type') }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" name="name" value="{{ old('name') }}" >
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>

                            <div class="float-right">
                                <button type="submit" class="btn btn-primary">{{ __('Speichern') }}</button>
                                <a class="btn btn-secondary" href="{{ route('trainings.templates.index') }}">{{ __('Abbrechen') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
