@section('title', __('Spielerakte') . ' - '. $user->Name)
@can('history', $user)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Spielerakte') }}
                </div>
                <div class="card-body">
                    <table class="table table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">{{ __('Fraktion/Unternehmen') }}</th>
                            <th scope="col">{{ __('Beitrittsdatum') }}</th>
                            <th scope="col">{{ __('Uninvite Datum') }}</th>
                            <th scope="col">{{ __('Dauer') }}</th>
                            <th scope="col">{{ __('Uninviter') }}</th>
                            <th scope="col">{{ __('Grund') }}</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->character->history as $history)
                            <tr>
                                <td>{{ $history->element->Name }}</td>
                                <td>{{ $history->JoinDate->format('d.m.Y H:i:s') }}</td>
                                <td>@if($history->LeaveDate){{ $history->LeaveDate->format('d.m.Y H:i:s') }}@else{{ '-' }}@endif</td>
                                <td>{{ $history->getDuration() }}</td>
                                <td>{{ $history->getUninviter() }}</td>
                                <td>@if($history->ExternalReason){{ $history->ExternalReason }}@else{{ '-' }}@endif</td>
                                <td><react-history-dialog data-history-id="{{ $history->Id }}"></react-history-dialog></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endcan
