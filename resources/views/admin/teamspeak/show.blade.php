@extends('layouts.app')

@section('title', __('Teamspeak'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                {{ __('Teamspeak Identität') }}
                            </div>
                            <div class="card-body">
                                Nickname: {{ $info->nickname }}

                                <table class="table table-sm">
                                    <tr>
                                        <th>Servergruppen</th>
                                    </tr>
                                    @foreach($serverGroups as $group)
                                        <tr>
                                            <td>{{ $group->name }}</td>
                                        </tr>
                                    @endforeach
                                </table>

                                <table class="table table-sm">
                                    <tr>
                                        <th>Channelgruppe</th>
                                        <th>Channel</th>
                                    </tr>
                                    @foreach($channelGroupMembers as $members)
                                        <tr>
                                            @foreach($channelGroups as $group)
                                                @if($group->id === $members->channelGroupId)
                                                <td>{{ $group->name }}</td>
                                                @endif
                                            @endforeach

                                            @foreach($channels as $channel)
                                                @if($channel->id === $members->channelId)
                                                    <td>{{ $channel->name }}</td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
