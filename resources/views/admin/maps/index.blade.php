@extends('layouts.app')

@section('title', __('Maps') . ' - ' . __('Admin'))

@section('content')
    <div class="container-fluid">
        @if(auth()->user()->Rank >= 7)
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('admin.maps.create') }}" class="btn btn-primary float-right">{{ __('Map hochladen') }}</a>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Exotische Maps') }}</div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-sm">
                            <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Ersteller') }}</th>
                                <th>{{ __('Wird gespeichert') }}</th>
                                <th>{{ __('Aktiviert?') }}</th>
                                <th>{{ __('Deaktivierbar?') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($maps as $entry)
                                <tr>
                                    <td>{{ $entry->Name }}</td>
                                    <td>@if($entry->creator)<a href="{{ route('users.show', [$entry->Creator]) }}">{{ $entry->creator->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->Creator }})@endif</td>
                                    <td>{{ $entry->SaveObjects ? __('Ja') : __('Nein') }}</td>
                                    <td>{{ $entry->Activated ? __('Ja') : __('Nein') }}</td>
                                    <td>{{ $entry->Deactivatable ? __('Ja') : __('Nein') }}</td>
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
