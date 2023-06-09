@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        @if(env('TEAMSPEAK_TROLL_ENABLED') === true)
        <div class="row">
            <div class="col-12 mb-4">
                <a href="/{{ env('TEAMSPEAK_TROLL_URI') }}" class="btn btn-primary btn-danger">{{ env('TEAMSPEAK_TROLL_NAME') }}</a>
            </div>
        </div>
        @endif
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
            <div class="col-xl-6 col-lg-12">
                <react-chart data-chart="online:statevsevilrelative" data-state="true" data-title="Relative Aktivität Staatsfraktionen vs Mafien & Gangs zu Anzahl Mitglieder"></react-chart>
            </div>
            <div class="col-xl-6 col-lg-12">
                <react-chart data-chart="online:total" data-state="true" data-title="Spieler online"></react-chart>
            </div>
        </div>
    </div>
@endsection
