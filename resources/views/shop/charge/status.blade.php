@extends('layouts.app')

@section('content')
  <!-- ============================================================== -->
  <!-- Container fluid  -->
  <!-- ============================================================== -->
  <div class="container-fluid">
    <!-- ============================================================== -->
    <!-- box -->
    <!-- ============================================================== -->
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-body  card2 pt-3">
            <div class="row">
              <div class="col-lg-6 col-md-9 font-18 font-weight-bold text-uppercase">Aufladen</div>
              <div class="col-lg-6 col-md-9 text-right font-16 font-weight-bold text-uppercase">
                    <a href="{{url('/')}}" class="btn btn-rounded btn-success m-b-20 waves-effect waves-light">Zurück zum Dashboard</a>
                </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                @if ($payment->status == "Success" || $payment->status == "Approved")
                    <div class="alert alert-success">
                        <h4>Die Zahlung über {{ $payment->exo_dollar }}€ durch {{ $payment->method }} wurde abgeschlossen!</h4>
                        Dein Guthaben ist in Kürze verfügbar!
                    </div>

                    <hr>
                    <h6>Weitere Details:</h6>
                    <div class="row">
                        <div class="col-3">Datum:</div>
                        <div class="col-3">{{ \Carbon\Carbon::parse($payment->created_at)->format('d.m.Y - H:i')}}
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-3">Zahlungsmethode:</div>
                        <div class="col-3">{{ $payment->method }}</div>
                    </div>
                    <div class="row">
                        <div class="col-3">Betrag in Euro:</div>
                        <div class="col-3">{{ $payment->amount }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-3">eXo-Dollar erhalten:</div>
                        <div class="col-3">{{ $payment->exo_dollar }} eXo-Dollar</div>
                    </div>
                @else
                    <div class="alert alert-danger">
                        <h4>Die Zahlung über {{ $payment->exo_dollar }}€ durch {{ $payment->method }} wurde abgebrochen!</h4>
                    </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Column -->
  </div>
  <!-- ============================================================== -->
  <!-- End box -->
@endsection
