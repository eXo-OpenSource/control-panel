@php
    $appends = [];

    $limit = request()->get('limit') ?? 25;

    if ($limit < 10)
        $limit = 10;

    if ($limit > 500)
        $limit = 500;

    if ($limit !== 25)
        $appends['limit'] = $limit;



    $query = \App\Models\BankAccountTransaction::query()->where('FromType', 1)->where('ToType', 1)->whereColumn('FromId', '<>', 'ToId')->orderBy('Id', 'DESC');

    if (request()->has('amount')) {
        $appends['amount'] = request()->get('amount');
        $query->where('Amount', '>=', request()->get('amount'));
    }

    $money = $query->paginate($limit);
@endphp
<div class="alert alert-danger" role="alert">
    {{ __('ACHTUNG: Hier werden nur direkte Zahlungen zwischen den Spielern angezeigt. Transaktionen über Fraktion, Unternehmen und Gruppen werden hier nicht angezeigt!') }}
</div>

<form method="GET" action="{{ route('admin.logs.show', ['transaction']) }}" class="mb-4">
    <div class="row">
        <div class="col-md-4">
            <div class="input-group">
                <input class="form-control" id="amount" name="amount" autocomplete="off" type="text" placeholder="Betrag" value="{{ request()->get('amount') }}">

                <select class="form-control" name="limit" id="limit">
                    <option value="10" @if($limit == 10){{ 'selected' }}@endif>10</option>
                    <option value="25" @if($limit == 25){{ 'selected' }}@endif>25</option>
                    <option value="50" @if($limit == 50){{ 'selected' }}@endif>50</option>
                    <option value="100" @if($limit == 100){{ 'selected' }}@endif>100</option>
                    <option value="250" @if($limit == 250){{ 'selected' }}@endif>250</option>
                    <option value="500" @if($limit == 500){{ 'selected' }}@endif>500</option>
                </select>

                <button type="submit" class="btn btn-sm btn-primary">{{ __('Absenden') }}</button>
            </div>
        </div>
    </div>
</form>

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
        <tr>
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
{{ $money->appends($appends)->links() }}
