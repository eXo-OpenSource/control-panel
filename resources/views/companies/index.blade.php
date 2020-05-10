@extends('layouts.app')

@section('title', __('Unternehmen'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('Unternehmen') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('# Mitglieder') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($companies as $company)
                                <tr>
                                    <td><a href="{{ route('companies.show', [$company->Id]) }}">{{ $company->Name }}</a></td>
                                    <td>{{ $company->membersCount() }}</td>
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
