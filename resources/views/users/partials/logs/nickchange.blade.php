@php
    $punish = $user->punish()->where('Type', 'nickchange')->with(['user', 'admin'])->orderBy('Id', 'DESC')->paginate(request()->get('limit') ?? 25);
@endphp
@section('title', __('Nickchanges') . ' - ' . __('Logs') . ' - '. $user->Name)
<table class="table table-sm w-full table-responsive-sm">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Admin') }}</th>
        <th>{{ __('Änderung') }}</th>
    </tr>
    @foreach($punish as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->admin)<a href="{{ route('users.show', [$entry->AdminId]) }}">{{ $entry->admin->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->AdminId }})@endif
            </td>
            <td>{{ $entry->Reason }}</td>
        </tr>
    @endforeach
</table>
{{ $punish->links() }}
