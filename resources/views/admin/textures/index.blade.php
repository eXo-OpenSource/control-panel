@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Fahrzeug-Texturen') }}</div>
                <div class="card-body">
                    <table class="table w-full">
                        <tr>
                            <th>Datum</th>
                            <th>User</th>
                            <th>Bild</th>
                            <th>Fahrzeug</th>
                            <th>Öffentlich</th>
                            <th>Status</th>
                        </tr>
                        @foreach($textures as $texture)
                        <tr>
                            <td>{{ $texture->Date->format('d.m.Y H:i:s') }}</td>
                            <td>@if($texture->user){{ $texture->user->Name }}@else{{ 'Unknown' }}@endif</td>
                            <td><img style="max-height: 160px;" src="@if(strpos($texture->Image, 'http') !== 0){{'https://picupload.pewx.de/textures/'}}@endif{{ $texture->Image }}"></td>
                            <td>@vehicleName($texture->Model)<br />
                                <img style="max-height: 150px;" src="https://exo-reallife.de/images/veh/Vehicle_{{ $texture->Model }}.jpg"></td>
                            <td>@if($texture->Public === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</td>
                            <td>{{ $texture->getStatus() }} - @if($texture->admin){{ $texture->admin->Name }}@else{{ 'Unknown' }}@endif</td>
                        </tr>
                        @endforeach
                    </table>

                    <div class="my-2">
                        {{ $textures->appends(['limit' => $limit])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
