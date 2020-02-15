@php
    $logins = $user->logins()->orderBy('Id', 'DESC')->paginate(25);
@endphp
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Aktion') }}</th>
        <th>{{ __('IP') }}</th>
        <th>{{ __('Serial') }}</th>
    </tr>
    @foreach($logins as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>{{ $entry->Type }}</td>
            <td>{{ $entry->Ip }}</td>
            <td>{{ $entry->Serial }}</td>
        </tr>
    @endforeach
</table>
{{ $logins->links() }}
