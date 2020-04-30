@extends('layouts.app')

@section('title', $template->Name . ' - ' . __('Vorlagen') . ' - ' . __('Schulungen'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-between align-items-start mb-4">
                <div>
                    <p class="h3">
                        {{ $template->Name }}
                    </p>
                </div>
                <div>
                    <a class="btn btn-success" href="{{ route('trainings.templates.trainings.create', [$template]) }}">{{ __('Starten') }}</a>
                    <a class="btn btn-primary" href="{{ route('trainings.templates.edit', [$template]) }}">{{ __('Bearbeiten') }}</a>
                    <a class="btn btn-danger" href="{{ route('trainings.templates.delete', [$template]) }}">{{ __('Löschen') }}</a>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between align-items-start m-4">
                <div>
                    <p class="h4">
                        {{ __('Inhalte') }}
                    </p>
                </div>
                <div>
                    <a class="float-right btn btn-primary" href="{{ route('trainings.templates.contents.create', [$template]) }}">{{ __('Inhalt hinzufügen') }}</a>
                </div>
            </div>
            @foreach($template->contents()->orderBy('Order', 'ASC')->orderBy('Id', 'ASC')->get() as $content)
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="h4">{{ $content->Name }}</span>
                                </div>
                                <div>
                                    <a href="{{ route('trainings.templates.contents.edit', [$content]) }}" class="btn btn-sm btn-primary">{{ __('Bearbeiten') }}</a>
                                    <a href="{{ route('trainings.templates.contents.delete', [$content]) }}" class="btn btn-sm btn-danger">{{ __('Löschen') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>Order: {{ $content->Order }}</p>
                            {{ $content->Description }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
