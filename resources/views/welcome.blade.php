@extends('layouts.app')

@section('content')
    <react-chart data-chart="factions" data-state="true" data-title="Aktivität"></react-chart>

    <react-chart data-chart="faction:1" data-state="true" data-title="Aktivität PD"></react-chart>

    <react-chart data-chart="faction:2" data-state="true" data-title="Aktivität FBI"></react-chart>

    <react-chart data-chart="faction:3" data-state="true" data-title="Aktivität SASF"></react-chart>
@endsection
