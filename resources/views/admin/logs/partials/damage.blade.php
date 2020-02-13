@php
    $damages = \App\Models\Logs\Damage::with(['user', 'target'])->orderBy('Id', 'DESC')->paginate(25);
@endphp
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Start') }}</th>
        <th>{{ __('Ende') }}</th>
        <th>{{ __('Schütze') }}</th>
        <th>{{ __('Ziel') }}</th>
        <th>{{ __('Waffe') }}</th>
        <th>{{ __('Schaden') }}</th>
        <th>{{ __('Treffer') }}</th>
        <th>{{ __('Position') }}</th>
    </tr>
    @foreach($damages as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->StartTime }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->UserId }})@endif
            </td>
            <td>
                @if($entry->target)<a href="{{ route('users.show', [$entry->TargetId]) }}">{{ $entry->target->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->TargetId }})@endif
            </td>
            <td>{{ $entry->Weapon }}</td>
            <td>{{ $entry->Damage }}</td>
            <td>{{ $entry->Hits }}</td>
            <td>{{ $entry->Position }}</td>
        </tr>
    @endforeach
</table>
{{ $damages->links() }}
