@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Spielzeit-Statistik') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-responsive-sm table-hover table-outline mb-0">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Spielzeit') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($playTime as $key => $row)
                                <tr>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->user->Id != -1)<a href="{{ route('users.show', [$row->user->Id]) }}">{{ $row->user->Name }}@else{{ $row->user->Name }}@endif</a></td>
                                    <td> @playTime($row->PlayTime)</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Bankgeld-Statistik') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-responsive-sm table-hover table-outline mb-0">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Bankgeld') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bankMoney as $key => $row)
                                <tr>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->owner->Id != -1)<a href="{{ route('users.show', [$row->owner->Id]) }}">{{ $row->owner->Name }}@else{{ $row->owner->Name }}@endif</a></td>
                                    <td>@money($row->Money)</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Fische gefangen') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-responsive-sm table-hover table-outline mb-0">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Fische gefangen') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($fishes as $key => $row)
                                <tr>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->user && $row->user->Id != -1)<a href="{{ route('users.show', [$row->user->Id]) }}">{{ $row->user->Name }}@else{{ $row->user ? $row->user->Name : "unbekannt" }}@endif</a></td>
                                    <td>{{number_format($row->FishCaught, 0, '', '.')}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Kilometer gefahren') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-responsive-sm table-hover table-outline mb-0">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Kilometer gefahren') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($driven as $key => $row)
                                <tr>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->user && $row->user->Id != -1)<a href="{{ route('users.show', [$row->user->Id]) }}">{{ $row->user->Name }}@else{{ $row->user ? $row->user->Name : "unbekannt" }}@endif</a></td>
                                    <td>{{number_format($row->Driven, 0, ',', '.')}}km</td>
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
