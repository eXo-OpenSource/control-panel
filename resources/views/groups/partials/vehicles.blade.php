<div class="row">
    @can('vehicles', $group)
        @foreach($group->vehicles as $vehicle)
            <div class="col-md-2">
                @include('partials.vehicle')
            </div>
        @endforeach
    @endcan
</div>
