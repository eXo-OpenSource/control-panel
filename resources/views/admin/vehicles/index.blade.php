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
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title">@vehicleName($vehicle->Model)</h5>
                                            <strong>{{ __('Gesamt: ') }} {{ $vehicle->Count }}</strong>
                                        </div>
                                        <div class="col">
                                            <button type="button" class="btn btn-light float-right" title="
                                                {{ __('Spielerbesitz: ') }} {{ $vehicle->PlayerCount }}<br>
                                                {{ __('Fraktionsbesitz: ') }} {{ $vehicle->FactionCount }}<br>
                                                {{ __('Unternehmensbesitz: ') }} {{ $vehicle->CompanyCount }}<br>
                                                {{ __('Firmen/Gang Besitz: ') }} {{ $vehicle->GroupCount }}<br>">
                                                <i class="fas fa-info-circle"></i>
                                          </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap.native/2.0.27/bootstrap-native-v4.min.js"></script>

    <script type="text/javascript">
        window.onload = function() {
            var elementsTooltip = document.querySelectorAll('[title]');

            for (var i = 0; i < elementsTooltip.length; i++){
                new Tooltip(elementsTooltip[i], {
                    placement: 'left',
                    animation: 'slideNfade',
                    delay: 150,
                })
            }
        }
    </script>
@endsection
