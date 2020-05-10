@extends('layouts.app')

@section('title', __('Schulung anlegen') . ' - ' .__('Schulungen'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Schulung anlegen') }}
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('trainings.templates.trainings.store', [$template]) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="notes">{{ __('Notizen') }}</label>
                                <textarea id="notes" rows="3" class="form-control{{ $errors->has('notes') ? ' is-invalid' : '' }}" placeholder="{{ __('Notizen') }}" name="notes">{{ old('notes') }}</textarea>
                                @if ($errors->has('notes'))
                                    <div class="invalid-feedback">{{ $errors->first('notes') }}</div>
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
