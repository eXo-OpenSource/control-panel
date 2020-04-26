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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Inhalte') }}
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12">
                                <a class="float-right btn btn-primary" href="{{ route('trainings.templates.contents.create', [$template]) }}">{{ __('Hinzufügen') }}</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-sm table-responsive-sm">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('Name') }}</th>
                                        <th scope="col">{{ __('Beschreibung') }}</th>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($template->contents()->orderBy('Order', 'ASC')->orderBy('Id', 'ASC')->get() as $content)
                                        <tr>
                                            <td>{{ $content->Name }}</td>
                                            <td>{{ $content->Description }}</td>
                                            <td>
                                                <a href="{{ route('trainings.templates.contents.edit', [$content]) }}" class="btn btn-sm btn-primary">{{ __('Bearbeiten') }}</a>
                                                <a href="{{ route('trainings.templates.contents.delete', [$content]) }}" class="btn btn-sm btn-danger">{{ __('Löschen') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
