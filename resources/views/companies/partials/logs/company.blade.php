@php
    $logs = $company->logs()->orderBy('Timestamp', 'DESC')->with('user')->with('user.user')->paginate(request()->get('limit') ?? 25);
@endphp
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Eintrag') }}</th>
        <th>{{ __('Datum') }}</th>
    </tr>
    @foreach($logs as $log)
        <tr>
            <td>@if($log->user and $log->user->user)<a href="{{ route('users.show', [$log->user->user->Id]) }}">{{ $log->user->user->Name }}</a>@else{{ 'Unknown' }}@endif {{ $log->Description }}</td>
            <td>{{ Carbon\Carbon::createFromTimestamp($log->Timestamp)->format('d.m.Y H:i:s') }}</td>
        </tr>
    @endforeach
</table>
{{ $logs->links() }}
