@php
    $kills = $user->kills()->with(['target'])->orderBy('Id', 'DESC')->paginate(request()->get('limit') ?? 25);
@endphp
@section('title', __('Morde') . ' - ' . __('Logs') . ' - '. $user->Name)
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Opfer') }}</th>
        <th>{{ __('Weapon') }}</th>
        <th>{{ __('Range') }}</th>
    </tr>
    @foreach($kills as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->target)<a href="{{ route('users.show', [$entry->TargetId]) }}">{{ $entry->target->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->TargetId }})@endif
            </td>
            <td>{{ $entry->Weapon }}</td>
            <td>{{ $entry->RangeBetween }}</td>
        </tr>
    @endforeach
</table>
{{ $kills->links() }}
