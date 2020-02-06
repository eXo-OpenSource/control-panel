<div class="row">
    @can('vehicles', $faction)
        @foreach($faction->vehicles as $vehicle)
            <div class="col-md-2">
                @include('partials.vehicle')
            </div>
        @endforeach
    @endcan
</div>
