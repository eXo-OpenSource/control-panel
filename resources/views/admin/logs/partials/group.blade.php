@php
    $groups = \App\Models\Logs\Group::with(['user', 'group'])->orderBy('Id', 'DESC')->paginate(request()->get('limit') ?? 25);
@endphp
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Gruppe') }}</th>
        <th>{{ __('Kategorie') }}</th>
        <th>{{ __('Spieler') }}</th>
        <th>{{ __('Beschreibung') }}</th>
    </tr>
    @foreach($groups as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->group)<a href="{{ $entry->group->getURL() }}">{{ $entry->group->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->GroupId }})@endif
            </td>
            <td>{{ $entry->Category }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->UserId }})@endif
            </td>
            <td>{{ $entry->Description }}</td>
        </tr>
    @endforeach
</table>
{{ $groups->links() }}
