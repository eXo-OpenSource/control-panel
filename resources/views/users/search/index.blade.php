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
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Spielzeit') }}</th>
                                @if(auth()->user()->Rank >= 3)
                                <th>{{ __('Letzter Login') }}</th>
                                <th>{{ __('Letzte IP') }}</th>
                                <th>{{ __('Letzte Serial') }}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr onclick="location.href = '{{ route('users.show', [$user->Id]) }}';" style="cursor: pointer;">
                                    <td>{{ $user->Name }}</td>
                                    <td>@if($user->character){{ $user->character->getPlayTime() }}@else{{ '-' }}@endif</td>
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
