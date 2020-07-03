@extends('layouts.app')

@section('title', __('Bans'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Bans') }}</div>
                <div class="card-body">
                    <table class="table w-full">
                        <tr>
                            <th>{{ __('Spieler')  }}</th>
                            <th>{{ __('Admin')  }}</th>
                            <th>{{ __('Bis')  }}</th>
                            <th>{{ __('Grund')  }}</th>
                        </tr>
                        @foreach($banList as $ban)
                            <tr>
                                <td><a href="{{ route('users.show', $ban->UserId) }}">{{ $ban->User }}</a></td>
                                <td>@if($ban->Admin)<a href="{{ route('users.show', $ban->AdminId) }}">{{ $ban->Admin }}</a>@else{{ '(ID: ' . $ban->AdminId . ')' }}@endif</td>
                                <td>@if($ban->Expires === 0){{ 'Permanent' }}@else{{ \Carbon\Carbon::now()->setTimestamp($ban->Expires)->format('d.m.Y H:i:s') }}@endif</td>
                                <td>{{ $ban->Reason }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
