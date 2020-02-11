@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    @foreach($vehicles as $vehicle)
                        <div class="col-md-2">
                            <div class="card">
                                <img class="bd-placeholder-img card-img-top" src="https://exo-reallife.de/images/veh/Vehicle_{{ $vehicle->Model }}.jpg">
                                <div class="card-body">
                                    <h5 class="card-title">@vehicleName($vehicle->Model)</h5>
                                    <dl class="vehicle-info">
                                        <dt>{{ __('Anzahl') }}</dt>
                                        <dd>{{ $vehicle->Count }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

