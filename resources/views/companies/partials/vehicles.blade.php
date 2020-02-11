<div class="row">
    @can('vehicles', $company)
        @foreach($company->vehicles as $vehicle)
            @include('partials.vehicle')
        @endforeach
    @endcan
</div>
