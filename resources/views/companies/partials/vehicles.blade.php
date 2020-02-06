<div class="row">
    @can('vehicles', $company)
        @foreach($company->vehicles as $vehicle)
            <div class="col-md-2">
                @include('partials.vehicle')
            </div>
        @endforeach
    @endcan
</div>
