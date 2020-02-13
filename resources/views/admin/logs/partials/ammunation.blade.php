@php
    $ammunation = \App\Models\Logs\Ammunation::with(['user'])->orderBy('Id', 'DESC')->paginate(25);
@endphp
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Spieler') }}</th>
        <th>{{ __('Type') }}</th>
        <th>{{ __('Kosten') }}</th>
        <th>{{ __('Position') }}</th>
        <th>{{ __('Waffen') }}</th>
    </tr>
    @foreach($ammunation as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->UserId }})@endif
            </td>
            <td>{{ $entry->Type }}</td>
            <td>@money($entry->Costs)</td>
            <td>{{ $entry->Position }}</td>
            <td>{{ $entry->Weapons }}</td>
        </tr>
    @endforeach
</table>
{{ $ammunation->links() }}
