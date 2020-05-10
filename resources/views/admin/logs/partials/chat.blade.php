@php
    $chat = \App\Models\Logs\Chat::with(['user'])->orderBy('Id', 'DESC')->simplePaginate(request()->get('limit') ?? 25);
@endphp
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Spieler') }}</th>
        <th>{{ __('Type') }}</th>
        <th>{{ __('Position') }}</th>
        <th>{{ __('Text') }}</th>
        <th>{{ __('Gehört von') }}</th>
    </tr>
    @foreach($chat as $entry)
        <tr>
            <td>{{ $entry->ID }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->UserId }})@endif
            </td>
            <td>{{ $entry->Type }}</td>
            <td>{{ $entry->Position }}</td>
            <td>{{ $entry->Text }}</td>
            <td>{{ $entry->Heared }}</td>
        </tr>
    @endforeach
</table>
{{ $chat->links() }}
