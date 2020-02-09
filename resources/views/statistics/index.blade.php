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
                        <table class="table table-responsive-sm table-hover table-outline mb-0">
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
                        <table class="table table-responsive-sm table-hover table-outline mb-0">
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
                                    <td>@if($row->ownerUser->Id != -1)<a href="{{ route('users.show', [$row->ownerUser->Id]) }}">{{ $row->ownerUser->Name }}@else{{ $row->ownerUser->Name }}@endif</a></td>
                                    <td>@money($row->Money)</td>
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
