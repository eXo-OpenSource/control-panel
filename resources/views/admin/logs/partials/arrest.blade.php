@php
    $arrests = \App\Models\Logs\Arrest::with(['user', 'police'])->orderBy('Id', 'DESC')->paginate(request()->get('limit') ?? 25);
@endphp
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Spieler') }}</th>
        <th>{{ __('Polizist') }}</th>
        <th>{{ __('Wanteds') }}</th>
        <th>{{ __('Dauer') }}</th>
        <th>{{ __('Bail') }}</th>
    </tr>
    @foreach($arrests as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->UserId }})@endif
            </td>
            <td>
                @if($entry->police)<a href="{{ route('users.show', [$entry->PoliceId]) }}">{{ $entry->police->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->PoliceId }})@endif
            </td>
            <td>{{ $entry->Wanteds }}</td>
            <td>{{ $entry->Duration }}</td>
            <td>@money($entry->Bail)</td>
        </tr>
    @endforeach
</table>
{{ $arrests->links() }}
