@extends('layouts.app')

@section('content')
    <div class="flex items-center">
        <div class="w-full ml-2 mr-2 md:w-2/3 md:mx-auto flex flex-col">
            <div class="mb-4">
                <a href="{{ route('textures.create') }}" class="btn btn-primary float-right">Textur hochladen</a>
            </div>
            <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">

                <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                    Fahrzeug-Texturen
                </div>

                <table class="table w-full">
                    <tr>
                        <th>Bild</th>
                        <th>Fahrzeug</th>
                        <th>Öffentlich</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    @foreach($textures as $texture)
                    <tr>
                        <td><img class="h-20" src="https://picupload.pewx.de/textures/{{ $texture->Image }}"></td>
                        <td>@vehicleName($texture->Model)
                            <img class="h-20 rounded" src="https://exo-reallife.de/images/veh/Vehicle_{{ $texture->Model }}.jpg"></td>
                        <td>@if($texture->Public === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</td>
                        <td>{{ $texture->getStatus() }}</td>
                        <td><form method="POST" action="{{ route('textures.destroy', [$texture->Id]) }}">@method('DELETE')@csrf<button type="submit"@if(!$texture->isDeleteable()){{'disabled'}}@endif class="btn btn-danger @if(!$texture->isDeleteable()){{'btn-disabled'}}@endif ">Löschen</button></form></td>
                    </tr>

                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
