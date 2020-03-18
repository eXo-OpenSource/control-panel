@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-12">
                <react-chart data-chart="activity:factions" data-state="true" data-title="Aktivität"></react-chart>
            </div>
            <div class="col-xl-6 col-lg-12">
                <react-chart data-chart="activity:companies" data-state="true" data-title="Aktivität"></react-chart>
            </div>
            <div class="col-xl-6 col-lg-12">
                <react-chart data-chart="online:statevsevil" data-state="true" data-title="Aktivität Staatsfraktionen vs Mafien & Gangs"></react-chart>
            </div>
        </div>
    </div>
@endsection
