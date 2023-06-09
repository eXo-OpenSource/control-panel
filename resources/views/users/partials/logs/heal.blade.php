@php
    $heals = $user->heal()->with(['user'])->orderBy('Id', 'DESC')->paginate(request()->get('limit') ?? 25);
@endphp
@section('title', __('Heilung') . ' - ' . __('Logs') . ' - '. $user->Name)
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Heilung') }}</th>
        <th>{{ __('Grund') }}</th>
        <th>{{ __('Position') }}</th>
    </tr>
    @foreach($heals as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>{{ $entry->Heal }}</td>
            <td>{{ $entry->Reason }}</td>
            <td>{{ $entry->Position }}</td>
        </tr>
    @endforeach
</table>
{{ $heals->links() }}
