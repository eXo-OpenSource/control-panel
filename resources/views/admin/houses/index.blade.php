@extends('layouts.app')

@section('title', __('Häuser'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Häuser') }}</div>
                <div class="card-body">
                    <table class="table table-sm w-full">
                        <tr>
                            <th>{{ __('Besitzer')  }}</th>
                            <th>{{ __('Preis')  }}</th>
                            <th>{{ __('Miete')  }}</th>
                            <th>{{ __('Interior')  }}</th>
                            <th>{{ __('Kasse')  }}</th>
                            <th>{{ __('Zuletzt online')  }}</th>
                            <th>{{ __('Frei in')  }}</th>
                        </tr>
                        @foreach($houses as $house)
                            <tr>
                                <td>@if($house['user'])<a href="{{ route('users.show', $house['user']['Id']) }}">{{ $house['user']['Name'] }}</a>@else{{ __('frei') }}@endif</td>
                                <td>{{ number_format($house['price'], 0, ',', ' ') }} $</td>
                                <td>{{ number_format($house['rentPrice'], 0, ',', ' ') }} $</td>
                                <td>{{ $house['interiorID'] }}</td>
                                <td>{{ number_format($house['bank']['Money'], 0, ',', ' ') }} $</td>
                                <td>@if($house['user']){{ (new \Carbon\Carbon($house['user']['LastLogin'])) }}@else{{ '-' }}@endif</td>
                                <td>@if($house['user']){{ __(':days Tage', ['days' => (new \Carbon\Carbon())->diffInDays((new \Carbon\Carbon($house['user']['LastLogin']))->addMonths(2), false)]) }}@else{{ '-' }}@endif</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
