@php
    $heals = $user->heal()->with(['user'])->orderBy('Id', 'DESC')->paginate(25);
@endphp
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Spieler') }}</th>
        <th>{{ __('Heilung') }}</th>
        <th>{{ __('Grund') }}</th>
        <th>{{ __('Position') }}</th>
    </tr>
    @foreach($heals as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->UserId }})@endif
            </td>
            <td>{{ $entry->Heal }}</td>
            <td>{{ $entry->Reason }}</td>
            <td>{{ $entry->Position }}</td>
        </tr>
    @endforeach
</table>
{{ $heals->links() }}
