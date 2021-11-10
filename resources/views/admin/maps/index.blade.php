@extends('layouts.app')

@section('title', __('Maps') . ' - ' . __('Admin'))

@section('content')
    <div class="container-fluid">
        @if(auth()->user()->Rank >= 7)
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-start">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-live-tab" data-toggle="pill" href="#pills-live" role="tab" aria-controls="pills-live" aria-selected="true">{{ __('Live') }}</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-dev-tab" data-toggle="pill" href="#pills-dev" role="tab" aria-controls="pills-dev" aria-selected="false">{{ __('Testserver') }}</a>
                    </li>
                </ul>

                <a href="{{ route('admin.maps.create') }}" class="btn btn-primary">{{ __('Map hochladen') }}</a>
            </div>
        </div>
        @endif
        <div class="tab-content" id="pills-tabContent">

            <div class="tab-pane fade show active" id="pills-live" role="tabpanel" aria-labelledby="pills-live-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">{{ __('Maps (Live Server)') }}</div>
                            <div class="card-body">
                                <table class="table table-responsive-sm table-sm">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Id') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Ersteller') }}</th>
                                        <th>{{ __('Objektanzahl') }}</th>
                                        <th>{{ __('Einstellungen') }}</th>
                                        <th>{{ __('zum Testserver übertragen') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($live_maps as $entry)
                                        <tr>
                                            <td>{{ $entry->Id }}</td>
                                            <td>{{ $entry->Name }}</td>
                                            <td>@if($entry->creator)<a href="{{ route('users.show', [$entry->Creator]) }}">{{ $entry->creator->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->Creator }})@endif</td>
                                            <td>{{ $entry->objectCount() }}</td>
                                            <td>
                                                @if($entry->SaveObjects)
                                                    <span class="badge badge-success">Perm. Objekte</span>
                                                @else
                                                    <span class="badge badge-danger">Perm. Objekte</span>
                                                @endif

                                                @if($entry->Activated)
                                                    <span class="badge badge-success">Aktiv</span>
                                                @else
                                                    <span class="badge badge-danger">Aktiv</span>
                                                @endif

                                                @if($entry->Deactivatable)
                                                    <span class="badge badge-success">Deaktivierbar</span>
                                                @else
                                                    <span class="badge badge-danger">Deaktivierbar</span>
                                                @endif
                                            </td>
                                            <td><react-map-copy-dialog data-id="{{ $entry->Id }}" data-connection="mysql"></react-prison-dialog></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="tab-pane fade" id="pills-dev" role="tabpanel" aria-labelledby="pills-dev-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">{{ __('Maps (Test Server)') }}</div>
                            <div class="card-body">
                                <table class="table table-responsive-sm table-sm">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Id') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Ersteller') }}</th>
                                        <th>{{ __('Objektanzahl') }}</th>
                                        <th>{{ __('Einstellungen') }}</th>
                                        <th>{{ __('zum Liveserver übertragen') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($dev_maps as $entry)
                                        <tr>
                                            <td>{{ $entry->Id }}</td>
                                            <td>{{ $entry->Name }}</td>
                                            <td>@if($entry->creator)<a href="{{ route('users.show', [$entry->Creator]) }}">{{ $entry->creator->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->Creator }})@endif</td>
                                            <td>{{ $entry->objectCount() }}</td>
                                            <td>
                                                @if($entry->SaveObjects)
                                                    <span class="badge badge-success">Perm. Objekte</span>
                                                @else
                                                    <span class="badge badge-danger">Perm. Objekte</span>
                                                @endif

                                                @if($entry->Activated)
                                                    <span class="badge badge-success">Aktiv</span>
                                                @else
                                                    <span class="badge badge-danger">Aktiv</span>
                                                @endif

                                                @if($entry->Deactivatable)
                                                    <span class="badge badge-success">Deaktivierbar</span>
                                                @else
                                                    <span class="badge badge-danger">Deaktivierbar</span>
                                                @endif
                                            </td>
                                            <td><react-map-copy-dialog data-id="{{ $entry->Id }}" data-connection="mysql_test"></react-prison-dialog></td>
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
