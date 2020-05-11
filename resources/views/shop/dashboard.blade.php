@extends('layouts.app')
@section('title', __('Shop'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 col-lg-4">
                <div class="card">
                    <div class="card-body p-0 d-flex align-items-center">
                        <i class="fas fa-star bg-success p-4 px-5 font-2xl mr-3"></i>
                        <div>
                            <div class="text-value-sm text-success">{{$days}}</div>
                            <div class="text-muted text-uppercase font-weight-bold small">Tage Premium</div>
                        </div>
                    </div>
                    <div class="card-footer px-3 py-2">
                    <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="#">
                            <span class="small font-weight-bold">verlängern</span>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-4">
                <div class="card">
                    <div class="card-body p-0 d-flex align-items-center">
                        <i class="fas fa-money-bill bg-primary p-4 px-5 font-2xl mr-3"></i>
                        <div>
                            <div class="text-value-sm text-primary">{{$dollars}}</div>
                            <div class="text-muted text-uppercase font-weight-bold small">eXo Dollar</div>
                        </div>
                    </div>
                    <div class="card-footer px-3 py-2">
                    <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="#">
                            <span class="small font-weight-bold">aufladen</span>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-4">
                <div class="card">
                    <div class="card-body p-0 d-flex align-items-center">
                        <i class="fas fa-car bg-danger p-4 px-5 font-2xl mr-3"></i>
                        <div>
                            <div class="text-value-sm text-danger">{{$vehicle_amount}}</div>
                            <div class="text-muted text-uppercase font-weight-bold small">Premium Fahrzeuge</div>
                        </div>
                    </div>
                    <div class="card-footer px-3 py-2">
                    <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="#">
                            <span class="small font-weight-bold">kaufen</span>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
