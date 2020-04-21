@php
    $punish = $user->punish()->where('Type', '<>', 'nickchange')->with(['user', 'admin'])->orderBy('Id', 'DESC');

    if(auth()->user()->Rank < 3) {
        $punish->where('Type', '<>', 'notice');
        $punish->where('DeletedAt', null);
    }

    $punish = $punish->paginate(25);
@endphp
@section('title', __('Strafen') . ' - ' . __('Logs') . ' - '. $user->Name)

@if(auth()->user()->Rank >= 3)
<react-punish-add-dialog class="float-right mb-4" data-id="{{ $user->Id }}"></react-punish-add-dialog>
@endif
<table class="table table-sm w-full table-responsive-sm">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Admin') }}</th>
        <th>{{ __('Type') }}</th>
        <th>{{ __('Grund') }}</th>
        @if(auth()->user()->Rank >= 3)
            <th>{{ __('Intern') }}</th>
        @endif
        <th>{{ __('Dauer') }}</th>
        @if(auth()->user()->Rank >= 3)
            <th></th>
        @endif
    </tr>
    @foreach($punish as $entry)
        <tr @if($entry->DeletedAt !== null)style="text-decoration: line-through;"@endif>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->admin)<a href="{{ route('users.show', [$entry->AdminId]) }}">{{ $entry->admin->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->AdminId }})@endif
            </td>
            <td>{{ $entry->Type }}</td>
            <td>{{ $entry->Reason }}</td>
            @if(auth()->user()->Rank >= 3)
            <td>{{ $entry->InternalMessage }}</td>
            @endif
            <td>
                @if($entry->Duration === 0)
                    {{ '-' }}
                @else
                    {{ $entry->Date->addSeconds($entry->Duration)->longAbsoluteDiffForHumans($entry->Date) }}
                    @if($entry->hasFixedEndDate())
                        {{ '- ' . $entry->Date->addSeconds($entry->Duration) }}
                    @endif
                @endif
            </td>
            @if(auth()->user()->Rank >= 3)
                <td>
                    <react-punish-history-dialog data-id="{{ $entry->Id }}"></react-punish-history-dialog>
                    <react-punish-edit-dialog data-id="{{ $entry->Id }}"></react-punish-edit-dialog>
                </td>
            @endif
        </tr>
    @endforeach
</table>
{{ $punish->links() }}
