@section('title', __('TeamSpeak') . ' - '. $user->Name)
@can('history', $user)
    <div class="row">
        <div class="col-md-12">
            <div class="row mb-4">
                <div class="col-12">
                    @if(auth()->user()->Rank >= 3)
                    <a href="{{ route('admin.users.teamspeak.create', [$user]) }}" class="btn btn-primary float-right">{{ __('Identität hinzufügen') }}</a>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            {{ __('TeamSpeak') }}
                        </div>
                        <div class="card-body">
                            <table class="table table-responsive-sm">
                                <thead>
                                <tr>
                                    <th scope="col">{{ __('Eindeutige ID') }}</th>
                                    <th scope="col">{{ __('Type') }}</th>
                                    @if(auth()->user()->Rank >= 3)
                                        <th scope="col">{{ __('Admin') }}</th>
                                        <th scope="col">{{ __('Notiz') }}</th>
                                    @endif
                                    <th scope="col">{{ __('Datum') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user->teamspeakIdentities as $identity)
                                    <tr>
                                        <td>{{ $identity->TeamspeakId }}</td>
                                        <td>@if($identity->Type === 1){{ 'Benutzer' }}@elseif($identity->Type === 2){{ 'Musikbot' }}@else{{ 'Unbekannt' }}@endif</td>
                                        @if(auth()->user()->Rank >= 3)
                                            <td>@if($identity->AdminId === null) {{ '-' }} @else @if($identity->admin)<a href="{{ route('users.show', [$identity->AdminId]) }}">{{ $identity->admin->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $identity->AdminId }}) @endif @endif</td>
                                            <td>{{ $identity->Notice }}</td>
                                        @endif
                                        <td>{{ $identity->CreatedAt->format('d.m.Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcan
