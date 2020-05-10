@extends('layouts.app')

@section('title', __('Inhalt anlegen') . ' - ' . __('Inhalte') . ' - ' . __('Vorlagen') .' - ' . __('Schulungen'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Inhalte anlegen') }}
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('trainings.templates.contents.store', [$template]) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" name="name" value="{{ old('name') }}" >
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="order">{{ __('Reihenfolge') }}</label>
                                <input type="number" id="order" class="form-control{{ $errors->has('order') ? ' is-invalid' : '' }}" placeholder="{{ __('Reihenfolge') }}" name="order" value="{{ old('order') }}" >
                                @if ($errors->has('order'))
                                    <div class="invalid-feedback">{{ $errors->first('order') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="description">{{ __('Beschreibung') }}</label>
                                <textarea id="description" rows="3" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Beschreibung') }}" name="description">{{ old('description') }}</textarea>
                                @if ($errors->has('description'))
                                    <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                @endif
                            </div>

                            <div class="float-right">
                                <button type="submit" class="btn btn-primary">{{ __('Speichern') }}</button>
                                <a class="btn btn-secondary" href="{{ route('trainings.templates.show', [$template]) }}">{{ __('Abbrechen') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
