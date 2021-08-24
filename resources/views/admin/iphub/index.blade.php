@extends('layouts.app')

@section('title', __('IP Hub'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                <p class="m-0">Typ 0: Residential or business IP (i.e. safe IP)</p>
                <p class="m-0">Typ 1: Non-residential IP (hosting provider, proxy, etc.)</p>
                <p class="m-0">Typ 2: Non-residential & residential IP (warning, may flag innocent people)</p>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('IP Hub') }}</div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.ip-hub.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input class="form-control" id="ip" name="ip" autocomplete="off" type="text" placeholder="IP" value="{{ request()->get('ip') }}">
                                    <input class="form-control" id="country" name="country" autocomplete="off" type="text" placeholder="Land" value="{{ request()->get('country') }}">
                                    <input class="form-control" id="block" name="block" autocomplete="off" type="text" placeholder="Typ" value="{{ request()->get('block') }}">

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
                    <table class="table table-sm w-full">
                        <tr>
                            <th scope="col"><a href="{{ route('admin.ip-hub.index', ['sortBy' => 'ip', 'direction' => $sortBy === 'ip' && $direction === 'asc'  ? 'desc' : 'asc', 'ip' => request()->get('ip'), 'country' => request()->get('country'), 'block' => request()->get('block')]) }}">{{ __('IP/Hostname') }}</a></th>
                            <th>{{ __('ISP/ASN')  }}</th>
                            <th scope="col"><a href="{{ route('admin.ip-hub.index', ['sortBy' => 'country', 'direction' => $sortBy === 'country' && $direction === 'asc'  ? 'desc' : 'asc', 'ip' => request()->get('ip'), 'country' => request()->get('country'), 'block' => request()->get('block')]) }}">{{ __('Land') }}</a></th>
                            <th scope="col"><a href="{{ route('admin.ip-hub.index', ['sortBy' => 'block', 'direction' => $sortBy === 'block' && $direction === 'asc'  ? 'desc' : 'asc', 'ip' => request()->get('ip'), 'country' => request()->get('country'), 'block' => request()->get('block')]) }}">{{ __('Typ') }}</a></th>
                        </tr>
                        @foreach($ips as $ip)
                            <tr>
                                <td><a href="{{ route('admin.ip-hub.show', ['ip' => $ip->Ip]) }}">{{ $ip->Ip }}</a><br>{{ $ip->Hostname }}</td>
                                <td>{{ $ip->ISP }}<br>{{ $ip->ASN }}</td>
                                <td>{{ $ip->CountryName }}</td>
                                <td>{{ $ip->Block }}</td>
                            </tr>
                        @endforeach
                    </table>

                    {{ $ips->appends($appends)->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
