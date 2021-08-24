@extends('layouts.app')

@section('title', $ipHub->Ip . ' - ' . __('IP Hub')))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            <p class="m-0">Typ 0: Residential or business IP (i.e. safe IP)</p>
                            <p class="m-0">Typ 1: Non-residential IP (hosting provider, proxy, etc.)</p>
                            <p class="m-0">Typ 2: Non-residential & residential IP (warning, may flag innocent people)</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                {{ __('IP Hub') . ': ' . $ipHub->Ip }}
                            </div>
                            <div class="card-body">
                                Hostname: {{ $ipHub->Hostname }}<br>
                                Land: {{ $ipHub->CountryName }}<br>
                                ASN: {{ $ipHub->ASN }}<br>
                                ISP: {{ $ipHub->ISP }}<br>
                                Block: {{ $ipHub->Block }}<br><br>

                                <table class="table table-sm">
                                    <tr>
                                        <th>Datum</th>
                                        <th>Name</th>
                                        <th>Serial</th>
                                        <th>Type</th>
                                    </tr>
                                    @foreach($ipHub->logins->sortByDesc('Date') as $login)
                                        <tr>
                                            <td>{{ $login->Date }}</td>
                                            <td><a href="{{ route('users.show', [$login->UserId]) }}">{{ $login->Name }}</a></td>
                                            <td>{{ $login->Serial }}</td>
                                            <td>{{ $login->Type }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
