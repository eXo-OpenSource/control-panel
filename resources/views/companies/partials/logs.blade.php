@php
    $logs = $company->logs()->orderBy('Timestamp', 'DESC')->with('user')->with('user.user')->paginate(25);
@endphp
@can('logs', $company)
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">{{ __('Logs') }}</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Eintrag</th>
                        <th>Datum</th>
                    </tr>
                    @foreach($logs as $log)
                        <tr>
                            <td>@if($log->user and $log->user->user)<a href="{{ route('users.show', [$log->user->user->Id]) }}">{{ $log->user->user->Name }}</a>@else{{ 'Unknown' }}@endif {{ $log->Description }}</td>
                            <td>{{ Carbon\Carbon::createFromTimestamp($log->Timestamp)->format('d.m.Y H:i:s') }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        {{ $logs->links() }}
    </div>
</div>
@endcan
