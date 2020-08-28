@extends('layouts.app')

@section('title', $template->Name . ' ' . __('bearbeiten') . ' - ' . __('Vorlagen') . ' - ' . __('Schulungen'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Vorlage bearbeiten') }}
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('trainings.templates.update', [$template]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" name="name" value="{{ old('name') ?? $template->Name }}" >
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="order">{{ __('Reihenfolge') }}</label>
                                <input type="number" id="order" class="form-control{{ $errors->has('order') ? ' is-invalid' : '' }}" placeholder="{{ __('Reihenfolge') }}" name="order" value="{{ old('order') ?? $template->Order }}" >
                                @if ($errors->has('order'))
                                    <div class="invalid-feedback">{{ $errors->first('order') }}</div>
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
