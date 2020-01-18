@php
    $damage = $user->damage()->with(['user', 'target'])->orderBy('Id', 'DESC')->paginate(25);
@endphp
<table class="table w-full">
    <tr>
        <th>Id</th>
        <th>Start</th>
        <th>Ende</th>
        <th>Schütze</th>
        <th>Ziel</th>
        <th>Waffe</th>
        <th>Schaden</th>
        <th>Treffer</th>
        <th>Position</th>
    </tr>
    @foreach($damage as $entry)
        <tr class="@if($entry->UserId == $user->Id){{'bg-gray-900'}}@else{{'bg-gray-800'}}@endif">
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->StartTime }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }}@endif
                                (ID: {{ $entry->UserId }})
            </td>
            <td>
                @if($entry->target)<a href="{{ route('users.show', [$entry->TargetId]) }}">{{ $entry->target->Name }}</a>@else{{ 'Unknown' }}@endif
                                (ID: {{ $entry->TargetId }})
            </td>
            <td>{{ $entry->Weapon }}</td>
            <td>{{ $entry->Damage }}</td>
            <td>{{ $entry->Hits }}</td>
            <td>{{ $entry->Position }}</td>
        </tr>
    @endforeach
</table>
{{ $damage->links() }}
