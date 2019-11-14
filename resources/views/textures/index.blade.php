@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row mb-4">
                    <a href="{{ route('textures.create') }}" class="btn btn-primary ml-auto">Textur hochladen</a>
                </div>
                <div class="row">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Gruppen') }}
                        </div>
                        <div class="card-body">
                            <table class="table table-responsive-sm mw-100">
                                <thead>
                                <tr>
                                    <th>Bild</th>
                                    <th>Fahrzeug</th>
                                    <th>Öffentlich</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($textures as $texture)
                                    <tr>
                                        <td><img class="img-fluid" style="max-height: 200px; max-width: 200px;" src="@if(strpos($texture->Image, 'http') !== 0){{'https://picupload.pewx.de/textures/'}}@endif{{ $texture->Image }}"></td>
                                        <td>@vehicleName($texture->Model)
                                            <img class="img-fluid rounded" src="https://exo-reallife.de/images/veh/Vehicle_{{ $texture->Model }}.jpg"></td>
                                        <td>@if($texture->Public === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</td>
                                        <td>{{ $texture->getStatus() }}</td>
                                        <td><form method="POST" action="{{ route('textures.destroy', [$texture->Id]) }}">@method('DELETE')@csrf<button type="submit"@if(!$texture->isDeleteable()){{'disabled'}}@endif class="btn btn-danger">Löschen</button></form></td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
