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
                <h4>Ein Problem ist aufgetreten!</h4>
                Die Zahlung wurde nicht durchgeführt!
                @if (isset($result))
                    {{ var_dump($result) }}
                @endif
                @if (session('error'))
                  <strong>Fehler:</strong> {{ session('error') }}
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

@push('js')
  <!-- Popup message jquery -->
  <script src="{{asset('plugins/vendors/toast-master/js/jquery.toast.js')}}"></script>
  <!-- Style switcher -->
  <script src="{{asset('plugins/vendors/styleswitcher/jQuery.style.switcher.js')}}"></script>
@endpush
