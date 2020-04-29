@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Teamspeak Freischaltungen') }}</div>
                    <div class="card-body">

                        <form method="GET" action="{{ route('admin.teamspeak.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input class="form-control" id="id" name="id" autocomplete="off" type="text" placeholder="Teamspeak ID" value="{{ request()->get('id') }}">

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
                                <th>{{ __('Teamspeak ID') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Admin') }}</th>
                                <th>{{ __('Notiz') }}</th>
                                <th>{{ __('Datum') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($teamspeak as $entry)
                                <tr>
                                    <td>@if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->UserId }})@endif</td>
                                    <td>{{ $entry->TeamspeakId }}</td>
                                    <td>@if($entry->Type === 1){{ 'Benutzer' }}@elseif($entry->Type === 2){{ 'Musikbot' }}@else{{ 'Unbekannt' }}@endif</td>
                                    <td>@if($entry->AdminId === null) {{ '-' }} @else @if($entry->admin)<a href="{{ route('users.show', [$entry->AdminId]) }}">{{ $entry->admin->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->AdminId }}) @endif @endif</td>
                                    <td>{{ $entry->Notice }}</td>
                                    <td>{{ $entry->CreatedAt->format('d.m.Y H:i:s') }}</td>
                                    <td><a class="btn btn-sm btn-danger" href="{{ route('admin.teamspeak.delete', $entry) }}">{{ __('Löschen') }}</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $teamspeak->appends(['id' => request()->get('id'), 'limit' => $limit])->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
