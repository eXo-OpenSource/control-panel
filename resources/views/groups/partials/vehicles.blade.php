<div class="row">
    @can('vehicles', $group)
        @foreach($group->vehicles as $vehicle)
            @include('partials.vehicle')
        @endforeach
    @endcan
</div>
