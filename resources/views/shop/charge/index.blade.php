@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    Aufladen <small class="d-block d-sm-inline-block mt-2 mt-sm-0 font-size-base font-w400 text-muted">Hier kannst du Geld auf dein Konto aufladen!</small>
                </h1>
                </div>
            </div>
        </div>

        <div class="content">
            @if (!auth()->user()->premium->hasBillingAdress())
                <div class="row">
                    <div class="col">
                        <div class="alert alert-warning" style="width: 100%;">
                        <strong>Warnung: </strong>Du hast deine Rechnungs-Anschrift noch nicht ausgefüllt. Du kannst so kein Guthaben aufladen.
                        <br><br>
                        <a class="btn btn-dark" href="{{ route('accounts.edit') }}">Rechnungs-Anschrift eingeben</a>
                        <div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-2 col-md-4 col-sm-6 js-appear-enabled animated fadeIn" data-toggle="appear" data-offset="50" data-class="animated fadeIn">
                    <a class="block block-link-pop" href="{{ route('charge.create', ['paypal']) }}">
                        <img class="img-fluid" src="{{asset('images/payment/paypal.png')}}" alt="">
                        <div class="block-content">
                            <h4 class="mb-1">PayPal</h4>
                            <p class="font-size-sm">
                                <span>ohne Gebühren</span>
                            </p>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 js-appear-enabled animated fadeIn" data-toggle="appear" data-offset="50" data-class="animated fadeIn">
                    <a class="block block-link-pop" href="{{ route('charge.create', ['klarna']) }}">
                        <img class="img-fluid" src="{{asset('images/payment/klarna.png')}}" alt="">
                        <div class="block-content">
                            <h4 class="mb-1">Klarna.</h4>
                            <p class="font-size-sm">
                                <span>ohne Gebühren</span>
                            </p>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 js-appear-enabled animated fadeIn" data-toggle="appear" data-offset="50" data-class="animated fadeIn">
                    <a class="block block-link-pop" href="{{ route('charge.create', ['paysafecard']) }}">
                        <img class="img-fluid" src="{{asset('images/payment/psc.png')}}" alt="">
                        <div class="block-content">
                            <h4 class="mb-1">paysafecard</h4>
                            <p class="font-size-sm">
                                <span class="text-danger">10% Gebühren</span>
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
