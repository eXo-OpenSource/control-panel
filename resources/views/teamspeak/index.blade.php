@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="mb-4 d-flex justify-content-between align-items-start">
                    <div></div>
                    <div>
                        <a class="btn btn-primary" href="{{ route('teamspeak.create') }}">{{ 'Hinzufügen' }}</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        {{ __('Teamspeak Identitäten') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Eindeutige ID') }}</th>
                                <th scope="col">{{ __('Type') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($teamspeakIdentities as $identity)
                                <tr>
                                    <td><a href="{{ route('teamspeak.show', [$identity->Id]) }}">{{ $identity->TeamspeakId  }}</a></td>
                                    <td>{{ $identity->getTypeName() }}</td>
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
