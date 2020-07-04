@extends('layouts.app')

@section('title', __('Fahrzeuge'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            {{ __('Alle Fahrzeuge mit dem Badge "Selten" sind nicht im Spiel erwerbar.') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($vehicles as $vehicle)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                            <div class="card">
                                @if(!$vehicle->IsInShop)<span class="image-badge image-badge-info">{{ __('Selten') }}</span>@endif
                                <img class="bd-placeholder-img card-img-top" src="https://exo-reallife.de/images/veh/Vehicle_{{ $vehicle->Id }}.jpg">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <span class="card-title h5">{{ $vehicle->Name }}</span>
                                            <button type="button" class="btn btn-light float-right" data-toggle="tooltip" data-html="true"
                                                    data-placement="left" data-animation="true" data-original-title="
                                                {{ __('Spielerbesitz: ') }} {{ $vehicle->PlayerOwned }}<br>
                                                {{ __('Fraktionsbesitz: ') }} {{ $vehicle->FactionOwned }}<br>
                                                {{ __('Unternehmensbesitz: ') }} {{ $vehicle->CompanyOwned }}<br>
                                                {{ __('Gruppenbesitz: ') }} {{ $vehicle->GroupOwned }}<br>">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <span class="d-block">{{ __('Gesamt: ') }} {{ $vehicle->Count }}</span>
                                            <span>{{ __('Handelbar: ') }} {{ $vehicle->TradeAbleCount }}</span>
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
    <script>
        document.querySelectorAll('[data-toggle="tooltip"]').forEach(function (element) {
            // eslint-disable-next-line no-new
            new coreui.Tooltip(element);
        });
    </script>
@endsection
