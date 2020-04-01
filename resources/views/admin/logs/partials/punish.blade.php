@php
    $punish = \App\Models\Logs\Punish::where('Type', '<>', 'nickchange')->with(['user', 'admin'])->orderBy('Id', 'DESC')->paginate(25);
@endphp
<table class="table table-sm w-full table-responsive-sm">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('User') }}</th>
        <th>{{ __('Admin') }}</th>
        <th>{{ __('Type') }}</th>
        <th>{{ __('Grund') }}</th>
        <th>{{ __('Dauer') }}</th>
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
            <td>
                @if($entry->Duration === 0)
                    {{ '-' }}
                @else
                    {{ $entry->Date->addSeconds($entry->Duration)->diffForHumans($entry->Date, 3) }}
                    @if($entry->hasFixedEndDate())
                        {{ '-' }}{{ $entry->Date->addSeconds($entry->Duration) }}
                    @endif
                @endif
            </td>
        </tr>
    @endforeach
</table>
{{ $punish->links() }}
