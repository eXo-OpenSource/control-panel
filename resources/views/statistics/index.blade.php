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
                                <tr @if(auth()->user() && $row->user && auth()->user()->Id == $row->user->Id)class="table-active"@endif>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->user->Id != -1)<a href="{{ route('users.show', [$row->user->Id]) }}">{{ $row->user->Name }}@else{{ $row->user->Name }}@endif</a></td>
                                    <td>{{ $row->getPlayTime() }}</td>
                                </tr>
                            @endforeach
                            @if($playTimeMyPosition && $playTimeMyPosition > 50)
                                <tr class="table-active">
                                    <td>{{ $playTimeMyPosition }}</td>
                                    <td><a href="{{ route('users.show', [auth()->user()->Id]) }}">{{ auth()->user()->Name }}</a></td>
                                    <td>{{ auth()->user()->character->getPlayTime() }}</td>
                                </tr>
                            @endif
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
                                <tr @if(auth()->user() && $row->owner && auth()->user()->Id == $row->owner->Id)class="table-active"@endif>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->owner->Id != -1)<a href="{{ route('users.show', [$row->owner->Id]) }}">{{ $row->owner->Name }}@else{{ $row->owner->Name }}@endif</a></td>
                                    <td>@money($row->Money)</td>
                                </tr>
                            @endforeach
                            @if($bankMoneyMyPosition && $bankMoneyMyPosition > 50)
                                <tr class="table-active">
                                    <td>{{ $bankMoneyMyPosition }}</td>
                                    <td><a href="{{ route('users.show', [auth()->user()->Id]) }}">{{ auth()->user()->Name }}</a></td>
                                    <td>{{ auth()->user()->character->bank->Money }}</td>
                                </tr>
                            @endif
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
                                <tr @if(auth()->user() && $row->user && auth()->user()->Id == $row->user->Id)class="table-active"@endif>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->user && $row->user->Id != -1)<a href="{{ route('users.show', [$row->user->Id]) }}">{{ $row->user->Name }}@else{{ $row->user ? $row->user->Name : "unbekannt" }}@endif</a></td>
                                    <td>{{number_format($row->FishCaught, 0, '', '.')}}</td>
                                </tr>
                            @endforeach
                            @if($fishesMyPosition && $fishesMyPosition > 50)
                                <tr class="table-active">
                                    <td>{{ $fishesMyPosition }}</td>
                                    <td><a href="{{ route('users.show', [auth()->user()->Id]) }}">{{ auth()->user()->Name }}</a></td>
                                    <td>{{ number_format(auth()->user()->character->stats->FishCaught, 0, '', '.') }}</td>
                                </tr>
                            @endif
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
                                <tr @if(auth()->user() && $row->user && auth()->user()->Id == $row->user->Id)class="table-active"@endif>
                                    <td>{{$key+1}}.</td>
                                    <td>@if($row->user && $row->user->Id != -1)<a href="{{ route('users.show', [$row->user->Id]) }}">{{ $row->user->Name }}@else{{ $row->user ? $row->user->Name : "unbekannt" }}@endif</a></td>
                                    <td>{{number_format($row->Driven, 0, ',', '.')}} km</td>
                                </tr>
                            @endforeach
                            @if($drivenMyPosition && $drivenMyPosition > 50)
                                <tr class="table-active">
                                    <td>{{ $drivenMyPosition }}</td>
                                    <td><a href="{{ route('users.show', [auth()->user()->Id]) }}">{{ auth()->user()->Name }}</a></td>
                                    <td>{{ number_format(auth()->user()->character->stats->Driven, 0, ',', '.') }} km</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
