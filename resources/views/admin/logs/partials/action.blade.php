@php
    $actions = \App\Models\Logs\Action::with(['user'])->orderBy('Id', 'DESC')->paginate(request()->get('limit') ?? 25);
@endphp
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Aktion') }}</th>
        <th>{{ __('Type') }}</th>
        <th>{{ __('Spieler') }}</th>
        <th>{{ __('Gruppe') }}</th>
    </tr>
    @foreach($actions as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>{{ $entry->Action }}</td>
            <td>{{ $entry->Type }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->UserId }})@endif
            </td>
            <td>{{ $entry->getGroupName() }}</td>
        </tr>
    @endforeach
</table>
{{ $actions->links() }}
