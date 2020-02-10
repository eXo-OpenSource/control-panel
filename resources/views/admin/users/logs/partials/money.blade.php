@php
    $money = $user->money()->orderBy('Id', 'DESC')->paginate(25);
@endphp
<table class="table w-full">
    <tr>
        <th>Id</th>
        <th>Datum</th>
        <th>Von</th>
        <th>Nach</th>
        <th>Betrag</th>
        <th>Grund</th>
        <th>Kategorie</th>
        <th>Unterkategorie</th>
    </tr>
    @foreach($money as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>{{ $entry->FromId }}, {{ $entry->FromType }}, {{ $entry->FromBank }}, @if($entry->from){{ $entry->from->Name }}@else{{ ' - ' }}@endif</td>
            <td>{{ $entry->ToId }}, {{ $entry->ToType }}, {{ $entry->ToBank }}, @if($entry->to){{ $entry->to->Name }}@else{{ ' - ' }}@endif</td>
            <td>{{ $entry->Amount }}</td>
            <td>{{ $entry->Reason }}</td>
            <td>{{ $entry->Category }}</td>
            <td>{{ $entry->Subcategory }}</td>
        </tr>
    @endforeach
</table>
{{ $money->links() }}
