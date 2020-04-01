@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Erfolge') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Beschreibung') }}</th>
                                <th scope="col">{{ __('Anzahl') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($achievements as $key => $row)
                                @if($row->achievement && $row->achievement->hidden === 0)
                                <tr>
                                    <td>{{ $row->achievement->name }}</td>
                                    <td>{{ $row->achievement->desc }}</td>
                                    <td>{{ $row->Count }}</td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
