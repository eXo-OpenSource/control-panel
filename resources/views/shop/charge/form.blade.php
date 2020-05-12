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
            <form method="POST" action={{url('charge/'.$type.'/pay')}}>
            @method('POST')
            @csrf
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
                    <div class="col-3 font-weight-bold text-dark">Zahlungsart:</div>
                    <div class="col">{{ $type }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-3 font-weight-bold text-dark">Gewünschter Betrag:</div>
                    <div class="col-3">
                        <div class="input-group">
                            <input id="amount" name="amount" class="form-control" type="number"/>
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">€</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                        <div class="col-3 font-weight-bold text-dark">Du erhälst:</div>
                        <div class="col"><span id="dollars">0 eXo Dollar</span>
                        @if ($type == "paysafecard")
                        <br><span class="text-danger">(aufgrund von 10% Bearbeitungsgebühren bei paysafecard)</span>
                        @endif
                    </div>
                    </div>
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

@push('script')
    <script>
        var amountElement = document.querySelector('#amount');
        amountElement.addEventListener('input', onAmountChanged);

        function onAmountChanged() {
            var type = '{{ $type }}';
            var amount = 0;
            if (Number(amountElement.value) > 0 ) {
                amount = Number(amountElement.value);
            }
            if (type == "paysafecard") {
                amount = Math.floor(amount * 0.9);
            }
            document.querySelector('#dollars').textContent = amount + " eXo Dollar";
        };
    </script>
@endpush

