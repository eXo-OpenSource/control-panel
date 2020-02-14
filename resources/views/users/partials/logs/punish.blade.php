@php
    $punish = $user->punish()->with(['user', 'admin'])->orderBy('Id', 'DESC')->paginate(25);
@endphp
<table class="table w-full">
    <tr>
        <th>Id</th>
        <th>Datum</th>
        <th>User</th>
        <th>Admin</th>
        <th>Type</th>
        <th>Grund</th>
        <th>Dauer</th>
    </tr>
    @foreach($punish as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->UserId }})@endif
            </td>
            <td>
                @if($entry->admin)<a href="{{ route('users.show', [$entry->AdminId]) }}">{{ $entry->admin->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->AdminId }})@endif
            </td>
            <td>{{ $entry->Type }}</td>
            <td>{{ $entry->Reason }}</td>
            <td>{{ $entry->Duration }}</td>
        </tr>
    @endforeach
</table>
{{ $punish->links() }}
