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
                <react-chart data-chart="activity:faction:1" data-state="true" data-title="Aktivität PD"></react-chart>
            </div>
            <div class="col-xl-6 col-lg-12">
                <react-chart data-chart="activity:faction:2" data-state="true" data-title="Aktivität FBI"></react-chart>
            </div>
            <div class="col-xl-6 col-lg-12">
                <react-chart data-chart="activity:faction:3" data-state="true" data-title="Aktivität SASF"></react-chart>
            </div>
        </div>
    </div>
@endsection
