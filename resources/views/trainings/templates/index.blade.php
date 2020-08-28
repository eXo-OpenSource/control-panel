@extends('layouts.app')

@section('title', __('Vorlagen') . ' - ' . __('Schulungen'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12 mb-4">
                <a class="float-right btn btn-primary" href="{{ route('trainings.templates.create') }}">{{ __('Hinzufügen') }}</a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Vorlagen') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Type') }}</th>
                                <th scope="col">{{ __('Reihenfolge') }}</th>
                                <th scope="col">{{ __('Name') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($templates as $template)
                                <tr>
                                    <td>{{ $template->getTarget() }}</td>
                                    <td>{{ $template->Order }}</td>
                                    <td><a href="{{ route('trainings.templates.show', [$template]) }}">{{ $template->Name }}</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
