@php
    $punish =$user->advert()->with(['user'])->orderBy('Id', 'DESC')->paginate(request()->get('limit') ?? 25);
@endphp
@section('title', __('Werbung') . ' - ' . __('Logs') . ' - '. $user->Name)
<table class="table table-sm w-full table-responsive-sm">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('User') }}</th>
        <th>{{ __('Text') }}</th>
    </tr>
    @foreach($punish as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->UserId }})@endif
            </td>
            <td>{{ $entry->Text }}</td>
        </tr>
    @endforeach
</table>
{{ $punish->links() }}
