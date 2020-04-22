@php
    $money = $user->money()->orderBy('Id', 'DESC')->paginate(request()->get('limit') ?? 25);
@endphp
@section('title', __('Geld') . ' - ' . __('Logs') . ' - '. $user->Name)
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Von') }}</th>
        <th>{{ __('Nach') }}</th>
        <th>{{ __('Betrag') }}</th>
        <th>{{ __('Grund') }}</th>
        <th>{{ __('Kategorie') }}</th>
        <th>{{ __('Unterkategorie') }}</th>
    </tr>
    @foreach($money as $entry)
        <tr class="@if($entry->ToType === 1 && $entry->ToId === $user->Id){{'tr-in'}}@else{{'tr-out'}}@endif">
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->from)
                    @if($entry->from->getURL())
                        <a href="{{ $entry->from->getURL() }}">{{ $entry->from->getName() }}</a>
                    @else
                        {{ $entry->from->getName() }}
                    @endif
                @else
                    {{ $entry->FromId }}, {{ $entry->FromType }}, {{ $entry->FromBank }}
                @endif
            </td>
            <td>
                @if($entry->to)
                    @if($entry->to->getURL())
                        <a href="{{ $entry->to->getURL() }}">{{ $entry->to->getName() }}</a>
                    @else
                        {{ $entry->to->getName() }}
                    @endif
                @else
                    {{ $entry->ToId }}, {{ $entry->ToType }}, {{ $entry->ToBank }}
                @endif
            </td>
            <td>{{ $entry->Amount }}</td>
            <td>{{ $entry->Reason }}</td>
            <td>{{ $entry->Category }}</td>
            <td>{{ $entry->Subcategory }}</td>
        </tr>
    @endforeach
</table>
{{ $money->links() }}
