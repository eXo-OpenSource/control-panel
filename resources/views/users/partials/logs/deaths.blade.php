@php
    $deaths = $user->deaths()->with(['user'])->orderBy('Id', 'DESC')->paginate(25);
@endphp
@section('title', __('Tode') . ' - ' . __('Logs') . ' - '. $user->Name)
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Mörder') }}</th>
        <th>{{ __('Weapon') }}</th>
        <th>{{ __('Range') }}</th>
    </tr>
    @foreach($deaths as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->UserId }})@endif
            </td>
            <td>{{ $entry->Weapon }}</td>
            <td>{{ $entry->RangeBetween }}</td>
        </tr>
    @endforeach
</table>
{{ $deaths->links() }}
