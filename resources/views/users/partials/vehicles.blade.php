@section('title', __('Fahrzeuge') . ' - '. $user->Name)
<div class="row">
    @can('vehicles', $user)
        @foreach($user->character->vehicles as $vehicle)
            @include('partials.vehicle')
        @endforeach
    @endcan
</div>
