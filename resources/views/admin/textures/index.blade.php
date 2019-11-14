@extends('layouts.app')

@section('content')
    <div class="flex items-center">
        <div class="w-full ml-2 mr-2 md:w-2/3 md:mx-auto flex flex-col">
            <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">

                <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                    Fahrzeug-Texturen
                </div>

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
                        <td><img class="h-20" src="@if(strpos($texture->Image, 'http') !== 0){{'https://picupload.pewx.de/textures/'}}@endif{{ $texture->Image }}"></td>
                        <td>@vehicleName($texture->Model)
                            <img class="h-20 rounded" src="https://exo-reallife.de/images/veh/Vehicle_{{ $texture->Model }}.jpg"></td>
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
@endsection
