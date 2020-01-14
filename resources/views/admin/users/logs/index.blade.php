@extends('layouts.app')

@section('top-menu')
    <ul class="c-header-nav d-md-down-none">
        <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{ route('admin.users.logs.index', [$user->Id]) }}">{{ __('Logs') }}</a></li>
    </ul>
@endsection

@section('content')

    <div class="container-fluid">
        <h3><a href="{{ route('users.show', [$user->Id]) }}">{{ $user->Name }}</a></h3>
        <hr>
        <div class="row justify-content-center">
            <div class="nav-tabs-boxed col-md-8">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#punish" role="tab" aria-controls="home" aria-selected="false">Punish</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kills" role="tab" aria-controls="profile" aria-selected="true">Kills</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#deaths" role="tab" aria-controls="messages">Deaths</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="punish" role="tabpanel">
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
                            @foreach($user->punish()->with(['user', 'admin'])->orderBy('Id', 'DESC')->get() as $punish)
                                <tr>
                                    <td>{{ $punish->Id }}</td>
                                    <td>{{ $punish->Date }}</td>
                                    <td>
                                        @if($punish->user)<a href="{{ route('users.show', [$punish->UserId]) }}">{{ $punish->user->Name }}</a>@else{{ 'Unknown' }}@endif
                                (ID: {{ $punish->UserId }})
                                    </td>
                                    <td>
                                        @if($punish->admin)<a href="{{ route('users.show', [$punish->AdminId]) }}">{{ $punish->admin->Name }}</a>@else{{ 'Unknown' }}@endif
                                (ID: {{ $punish->AdminId }})
                                    </td>
                                    <td>{{ $punish->Type }}</td>
                                    <td>{{ $punish->Reason }}</td>
                                    <td>{{ $punish->Duration }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="tab-pane" id="kills" role="tabpanel">
                        <table class="table w-full">
                            <tr>
                                <th>Id</th>
                                <th>Datum</th>
                                <th>User</th>
                                <th>Target</th>
                                <th>Weapon</th>
                                <th>Range</th>
                            </tr>
                            @foreach($user->kills()->with(['user', 'target'])->limit(100)->orderBy('Id', 'DESC')->get() as $punish)
                                <tr>
                                    <td>{{ $punish->Id }}</td>
                                    <td>{{ $punish->Date }}</td>
                                    <td>
                                        @if($punish->user)<a href="{{ route('users.show', [$punish->UserId]) }}">{{ $punish->user->Name }}</a>@else{{ 'Unknown' }}@endif
                                (ID: {{ $punish->UserId }})
                                    </td>
                                    <td>
                                        @if($punish->target)<a href="{{ route('users.show', [$punish->TargetId]) }}">{{ $punish->target->Name }}</a>@else{{ 'Unknown' }}@endif
                                (ID: {{ $punish->TargetId }})
                                    </td>
                                    <td>{{ $punish->Weapon }}</td>
                                    <td>{{ $punish->Range }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="tab-pane" id="deaths" role="tabpanel">
                        <table class="table w-full">
                            <tr>
                                <th>Id</th>
                                <th>Datum</th>
                                <th>User</th>
                                <th>Target</th>
                                <th>Weapon</th>
                                <th>Range</th>
                            </tr>
                            @foreach($user->deaths()->with(['user', 'target'])->limit(100)->orderBy('Id', 'DESC')->get() as $punish)
                                <tr>
                                    <td>{{ $punish->Id }}</td>
                                    <td>{{ $punish->Date }}</td>
                                    <td>
                                        @if($punish->user)<a href="{{ route('users.show', [$punish->UserId]) }}">{{ $punish->user->Name }}</a>@else{{ 'Unknown' }}@endif
                                (ID: {{ $punish->UserId }})
                                    </td>
                                    <td>
                                        @if($punish->target)<a href="{{ route('users.show', [$punish->TargetId]) }}">{{ $punish->target->Name }}</a>@else{{ 'Unknown' }}@endif
                                (ID: {{ $punish->TargetId }})
                                    </td>
                                    <td>{{ $punish->Weapon }}</td>
                                    <td>{{ $punish->Range }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
