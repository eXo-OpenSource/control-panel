@php
    $deaths = $user->heal()->with(['user'])->orderBy('Id', 'DESC')->paginate(25);
@endphp
<table class="table w-full">
    <tr>
        <th>Id</th>
        <th>Datum</th>
        <th>Spieler</th>
        <th>Heilung</th>
        <th>Grund</th>
        <th>Position</th>
    </tr>
    @foreach($deaths as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }}@endif
                                (ID: {{ $entry->UserId }})
            </td>
            <td>{{ $entry->Heal }}</td>
            <td>{{ $entry->Reason }}</td>
            <td>{{ $entry->Position }}</td>
        </tr>
    @endforeach
</table>
{{ $deaths->links() }}
