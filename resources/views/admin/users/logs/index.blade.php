@extends('layouts.app')

@section('top-menu')
    <ul class="c-header-nav d-md-down-none">
        <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{ route('admin.users.logs.index', [$user->Id]) }}">{{ __('Logs') }}</a></li>
    </ul>
@endsection

@section('content')

    <div class="container-fluid">
        <h3>{{ $user->Name }}</h3>
        <hr>
        <div class="row justify-content-center">
            <div class="card col-md-12">
                <table class="table w-full">
                    <tr>
                        <th>Id</th>
                        <th>Datum</th>
                        <th>User</th>
                        <th>Admin</th>
                        <th>Type</th>
                        <th>Grund</th>
                        <th>Dauer</th>
                    </tr>
                    @foreach($user->punish as $punish)
                        <tr>
                            <td>{{ $punish->Id }}</td>
                            <td>{{ $punish->Date }}</td>
                            <td>@if($punish->user){{ $punish->user->Name }}@else{{ 'Unknown' }}@endif</td>
                            <td>@if($punish->admin){{ $punish->admin->Name }}@else{{ 'Unknown' }}@endif</td>
                            <td>{{ $punish->Type }}</td>
                            <td>{{ $punish->Reason }}</td>
                            <td>{{ $punish->Duration }}</td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>
@endsection
