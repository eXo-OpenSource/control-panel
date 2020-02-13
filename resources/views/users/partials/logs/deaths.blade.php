@php
    $deaths = $user->deaths()->with(['user', 'target'])->orderBy('Id', 'DESC')->paginate(25);
@endphp
<table class="table w-full">
    <tr>
        <th>Id</th>
        <th>Datum</th>
        <th>User</th>
        <th>Target</th>
        <th>Weapon</th>
        <th>Range</th>
    </tr>
    @foreach($deaths as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
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
            <td>{{ $entry->RangeBetween }}</td>
        </tr>
    @endforeach
</table>
{{ $deaths->links() }}
