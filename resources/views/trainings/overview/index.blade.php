@extends('layouts.app')

@section('title', __('Übersicht') . ' - ' . __('Schulungen'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Übersicht') }}
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-3">
                                <form method="GET" action="{{ route('trainings.overview.index') }}">
                                    @if(count($targets) > 1)
                                    <div class="form-row mb-2">
                                        <div class="col">
                                            <div class="btn-group" role="group">
                                                @if(in_array('faction', $targets))<button class="btn btn-secondary @if($currentTarget === 'faction'){{ 'active' }}@endif" href="{{ route('trainings.overview.index', ['target' => 'faction', 'role' => $role, 'fromDate' => $dateFrom->format('Y-m-d'), 'toDate' => $dateTo->format('Y-m-d')]) }}">{{ __('Fraktion') }}</button>@endif
                                                @if(in_array('company', $targets))<button class="btn btn-secondary @if($currentTarget === 'company'){{ 'active' }}@endif" href="{{ route('trainings.overview.index', ['target' => 'company', 'role' => $role, 'fromDate' => $dateFrom->format('Y-m-d'), 'toDate' => $dateTo->format('Y-m-d')]) }}">{{ __('Unternehmen') }}</button>@endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="form-row mb-2">
                                        <div class="col">
                                            <div class="btn-group" role="group">
                                                <a class="btn btn-secondary @if($role === 0){{ 'active' }}@endif" href="{{ route('trainings.overview.index', ['role' => 0, 'target' => $currentTarget, 'fromDate' => $dateFrom->format('Y-m-d'), 'toDate' => $dateTo->format('Y-m-d')]) }}">{{ __('Teilgenommen') }}</a>
                                                <a class="btn btn-secondary @if($role === 1){{ 'active' }}@endif" href="{{ route('trainings.overview.index', ['role' => 1, 'target' => $currentTarget, 'fromDate' => $dateFrom->format('Y-m-d'), 'toDate' => $dateTo->format('Y-m-d')]) }}">{{ __('Ausgebildet') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <input type="text" class="form-control" name="fromDate" value="{{ $dateFrom->format('Y-m-d') }}">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" name="toDate" value="{{ $dateTo->format('Y-m-d') }}">
                                        </div>
                                        <input type="hidden" class="form-control" name="target" value="{{ $currentTarget }}">
                                        <input type="hidden" class="form-control" name="role" value="{{ $role }}">
                                        <div class="col">
                                            <button type="submit" class="btn btn-primary">{{ __('Aktualisieren') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <table class="table-header-rotated">
                                    <thead>
                                    <tr>
                                        @foreach($matrixInfo['title'] as $title)
                                            @if($title['Value'] === '')
                                                <th></th>
                                            @else
                                                <th class="rotate"><div><span>{{ $title['Value'] }}</span></div></th>
                                            @endif
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($matrixInfo['rows'] as $row)
                                        <tr>
                                            @foreach($row as $data)
                                                @if($data['UserId'])
                                                    <th class="row-header"><a href="{{ route('users.show', [$data['UserId']]) }}">{{ $data['Value'] }}</a></th>
                                                @elseif($data['Rank'])
                                                    <th class="row-header">{{ $data['Value'] }}</th>
                                                @elseif($data['Sum'])
                                                    <th class="row-header">{{ $data['Value'] }}</th>
                                                @else
                                                    @if($role === 0)
                                                        <td>@if($data['Value'] > 0)<i class="fas fa-check" style="color: rgb(69, 161, 100);"></i>@else<i class="fas fa-times" style="color: rgb(209, 103, 103);"></i>@endif</td>
                                                    @else
                                                        <td>@if($data['Value'] > 0){{ $data['Value'] }}@endif</td>
                                                    @endif
                                                @endif
                                            @endforeach
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
    </div>
@endsection
