@php
    /** @var \App\Models\Faction $faction */
    $offset = request()->get('offset');
    $limit = request()->get('limit') ?? 50;

    $factionId = $faction->Id;
    if($factionId === 2 || $factionId === 3)
        $factionId = 1;

    $from = \App\Models\BankAccountTransaction::query()->where('FromType', 2)->where('FromId', $factionId)->orderBy('Id', 'DESC')->limit($limit);
    $to = \App\Models\BankAccountTransaction::query()->where('ToType', 2)->where('ToId', $factionId)->orderBy('Id', 'DESC')->limit($limit);

    if($offset) {
        $from->where('Id', '<', $offset);
        $to->where('Id', '<', $offset);
    }

    $money = $to->union($from)->orderBy('Id', 'DESC')->limit($limit)->get();

    $next_offset = $money->last()->Id;
@endphp
@section('title', __('Geld') . ' - ' . __('Logs') . ' - '. $faction->Name)
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
        <tr class="@if($entry->ToType === 2 && $entry->ToId === $faction->Id){{'tr-in'}}@else{{'tr-out'}}@endif">
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
<nav>
    <ul class="pagination">
        <li class="page-item">
            <a class="page-link" href="{{ route('factions.show.logs', ['faction' => $faction->Id, 'log' => 'money', 'offset' => $next_offset]) }}" rel="next">Weiter »</a>
        </li>
    </ul>
</nav>
