@php
    $logins = \App\Models\Logs\Login::with(['user'])->orderBy('Id', 'DESC')->paginate(25);
@endphp
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Spieler') }}</th>
        <th>{{ __('Aktion') }}</th>
        <th>{{ __('IP') }}</th>
        <th>{{ __('Serial') }}</th>
    </tr>
    @foreach($logins as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->UserId }})@endif
            </td>
            <td>{{ $entry->Type }}</td>
            <td>{{ $entry->Ip }}</td>
            <td>{{ $entry->Serial }}</td>
        </tr>
    @endforeach
</table>
{{ $logins->links() }}
