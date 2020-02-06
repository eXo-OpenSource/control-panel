<div class="row">
    @can('vehicles', $user)
        @foreach($user->character->vehicles as $vehicle)
            <div class="col-md-2">
                @include('partials.vehicle')
            </div>
        @endforeach
    @endcan
</div>
