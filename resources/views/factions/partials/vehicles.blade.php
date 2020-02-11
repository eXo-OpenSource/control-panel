<div class="row">
    @can('vehicles', $faction)
        @foreach($faction->vehicles as $vehicle)
            @include('partials.vehicle')
        @endforeach
    @endcan
</div>
