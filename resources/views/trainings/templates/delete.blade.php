@extends('layouts.app')

@section('title', $template->Name . ' ' . __('löschen') . ' - ' . __('Vorlagen') . ' - ' . __('Schulungen'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Vorlage löschen') }}
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('trainings.templates.destroy', [$template]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('DELETE')

                            <div class="form-group">
                                <label for="uniqueId">{{ __('Name') }}</label>
                                <input type="text" id="uniqueId" class="form-control" disabled name="name" value="{{ $template->Name }}" >
                            </div>

                            <div class="float-right">
                                <button type="submit" class="btn btn-danger">{{ __('Löschen') }}</button>
                                <a class="btn btn-primary" href="{{ route('trainings.templates.show', [$template]) }}">{{ __('Abbrechen') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
