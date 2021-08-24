@php
    $logins = \App\Models\Logs\Login::with(['user', 'ipHub'])->orderBy('Id', 'DESC')->paginate(request()->get('limit') ?? 25);
@endphp
<table class="table table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Datum') }}</th>
        <th>{{ __('Spieler') }}</th>
        <th>{{ __('Aktion') }}</th>
        <th>{{ __('IP') }}</th>
        <th>{{ __('Serial') }}</th>
    </tr>
    @foreach($logins as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->UserId }})@endif
            </td>
            <td>{{ $entry->Type }}</td>
            <td>
                @if(!isset($entry->ipHub))
                    <i class="fas fa-network-wired text-muted"
                        data-toggle="tooltip"
                        data-trigger="hover focus click"
                        data-placement="right"
                        data-animation="true"
                        data-original-title="Es existieren noch keine Informationen zu dieser IP.">
                    </i>
                @else
                    <i class="fas fa-network-wired @if($entry->ipHub->Block === 0){{ 'text-success' }}@elseif($entry->ipHub->Block === 1){{ 'text-danger' }}@else{{ 'text-warning' }}@endif"
                        data-toggle="tooltip"
                        data-trigger="hover focus click"
                        data-placement="right"
                        data-animation="true"
                        data-html="true"
                        data-original-title="Typ: {{ ($entry->ipHub->Block === 0 ? 'Residential or business IP' : ($entry->ipHub->Block === 1 ? 'Non-residential IP (hosting provider, proxy, etc.)' : 'Non-residential & residential IP (warning, may flag innocent people)')) }}<br>Hostname: {{ $entry->ipHub->Hostname }}<br>ISP: {{ $entry->ipHub->ISP }}<br>ASN: {{ $entry->ipHub->ASN }}<br>Land: {{ $entry->ipHub->CountryName }}">
                    </i>
                @endif
                {{ $entry->Ip }}
            </td>
            <td>{{ $entry->Serial }}</td>
        </tr>
    @endforeach
</table>
{{ $logins->links() }}
