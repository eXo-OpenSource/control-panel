@extends('layouts.app')

@push('before-css')
  <!-- Page CSS -->
  <link href="{{asset('plugins/vendors/bootstrap-checkbox/awesome-bootstrap-checkbox.css')}}" rel="stylesheet" type="text/css">
@endpush

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
            <form method="POST" action={{ route('charge.store') }}>
            @method('POST')
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
          <div class="card-body  card2 pt-3">
            <div class="row">
              <div class="col-lg-6 col-md-9 font-18 font-weight-bold text-uppercase">Aufladen</div>
              <div class="col-lg-6 col-md-9 text-right font-16 font-weight-bold text-uppercase">
                    <button type="submit" class="btn btn-rounded btn-success m-b-20 waves-effect waves-light">Weiter</button>
                </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                   @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                          @endforeach
                      </ul>
                    </div><br />
                  @endif
                <div class="row mb-2">
                    <div class="col-3 font-weight-bold">Zahlungsart:</div>
                    <div class="col">{{ $type }}</div>
                </div>
                <react-charge-form data-paymenttype="{{ $type }}"></react-charge-form>
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
