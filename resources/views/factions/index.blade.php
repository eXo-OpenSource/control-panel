@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card col-md-8">
                <div class="card-header">
                    {{ __('Fraktionen') }}
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('# Mitglieder') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($factions as $faction)
                            <tr>
                                <td><a href="{{ route('factions.show', [$faction->Id]) }}">{{ $faction->Name }}</a></td>
                                <td>{{ $faction->membersCount() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
