@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Benutzersuche') }}</div>
                    <div class="card-body">

                        <form method="GET" action="{{ route('users.search') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input class="form-control" id="name" name="name" autocomplete="off" type="text" placeholder="Name" value="{{ request()->get('name') }}">
                                        @if(auth()->user()->Rank >= 3)
                                        <input class="form-control" id="serial" name="serial" autocomplete="off" type="text" placeholder="Serial" value="{{ request()->get('serial') }}">
                                        <input class="form-control" id="ip" name="ip" autocomplete="off" type="text" placeholder="IP" value="{{ request()->get('ip') }}">
                                        @endif

                                        <select class="form-control" name="limit" id="limit">
                                            <option value="10" @if($limit == 10){{ 'selected' }}@endif>10</option>
                                            <option value="25" @if($limit == 25){{ 'selected' }}@endif>25</option>
                                            <option value="50" @if($limit == 50){{ 'selected' }}@endif>50</option>
                                            <option value="100" @if($limit == 100){{ 'selected' }}@endif>100</option>
                                            <option value="250" @if($limit == 250){{ 'selected' }}@endif>250</option>
                                            <option value="500" @if($limit == 500){{ 'selected' }}@endif>500</option>
                                        </select>

                                        <button type="submit" class="btn btn-sm btn-primary">{{ __('Absenden') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>


                        <table class="table table-responsive-sm table-sm">
                            <thead>
                            <tr>
                                @if(auth()->user()->Rank >= 3)
                                <th>{{ __('Status') }}</th>
                                @endif
                                <th scope="col"><a href="{{ route('users.search', ['sortBy' => 'name', 'direction' => $sortBy === 'name' && $direction === 'asc'  ? 'desc' : 'asc', 'name' => request()->get('name')]) }}">{{ __('Name') }}</a></th>
                                <th scope="col"><a href="{{ route('users.search', ['sortBy' => 'playTime', 'direction' => $sortBy === 'playTime' && $direction === 'asc'  ? 'desc' : 'asc', 'playTime' => request()->get('playTime')]) }}">{{ __('Spielzeit') }}</a></th>
                                @if(auth()->user()->Rank >= 3)
                                <th>{{ __('Letzter Login') }}</th>
                                <th>{{ __('Letzte IP') }}</th>
                                <th>{{ __('Letzte Serial') }}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    @if(auth()->user()->Rank >= 3)
                                    <td>
                                        @if($user->isBanned() !== false)
                                            <i class="fas fa-gamepad text-danger"
                                               data-toggle="tooltip"
                                               data-placement="right"
                                               data-animation="true"
                                               data-original-title="@if($user->isBanned() === 0){{ 'Permanent' }}@else{{ \Carbon\Carbon::now()->setTimestamp($user->isBanned())->format('d.m.Y H:i:s') }}@endif">
                                            </i>
                                        @else
                                            <i class="fas fa-gamepad text-success"></i>
                                        @endif
                                        @if($user->isTeamSpeakBanned())
                                            <i class="fab fa-teamspeak text-danger"
                                               data-toggle="tooltip"
                                               data-placement="right"
                                               data-animation="true"
                                               data-original-title="@if($user->isTeamSpeakBanned() === 0){{ 'Permanent' }}@else{{ \Carbon\Carbon::now()->addSeconds($user->isTeamSpeakBanned())->format('d.m.Y H:i:s') }}@endif">
                                            </i>
                                        @else
                                            <i class="fab fa-teamspeak text-success"></i>
                                        @endif
                                    </td>
                                    @endif
                                    <td><a href="{{ route('users.show', [$user->Id]) }}">{{ $user->Name }}</a></td>
                                    <td>@playTime($user->PlayTime)</td>
                                    @if(auth()->user()->Rank >= 3)
                                    <td>{{ $user->LastLogin->format('d.m.Y H:i:s') }}</td>
                                    <td>{{ $user->LastIP }}</td>
                                    <td>{{ $user->LastSerial }}</td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $users->appends($appends)->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    @if(auth()->user()->Rank >= 3)
    <script>
        document.querySelectorAll('[data-toggle="tooltip"]').forEach(function (element) {
            // eslint-disable-next-line no-new
            new coreui.Tooltip(element);
        });
    </script>
    @endif
@endsection
