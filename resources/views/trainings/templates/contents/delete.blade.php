@extends('layouts.app')

@section('title', $content->Name . ' ' . __('löschen') . ' - ' . __('Inhalte') . ' - ' . __('Vorlagen') .' - ' . __('Schulungen'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Inhalt löschen') }}
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('trainings.templates.contents.destroy', [$content]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('DELETE')

                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" id="name" class="form-control" disabled name="name" value="{{ $content->Name }}" >
                            </div>

                            <div class="form-group">
                                <label for="order">{{ __('Reihenfolge') }}</label>
                                <input type="number" id="order" class="form-control" disabled name="order" value="{{ $content->Order }}" >
                            </div>

                            <div class="form-group">
                                <label for="description">{{ __('Beschreibung') }}</label>
                                <textarea id="description" rows="3" class="form-control" disabled name="description">{{ $content->Description }}</textarea>
                            </div>

                            <div class="float-right">
                                <button type="submit" class="btn btn-danger">{{ __('Löschen') }}</button>
                                <a class="btn btn-primary" href="{{ route('trainings.templates.show', [$content->template]) }}">{{ __('Abbrechen') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
