<div class="card">
    <img class="bd-placeholder-img card-img-top" src="https://exo-reallife.de/images/veh/Vehicle_{{ $vehicle->Model }}.jpg">
    <div class="card-body">
        <h5 class="card-title">{{ $vehicle->getName() }} - {{ $vehicle->Id }}</h5>
        <dl class="vehicle-info">
            <dt>Kilometerstand</dt>
            <dd>{{ number_format($vehicle->Mileage / 1000, 2, ',', ' ') }} km</dd>
            <dt>Lackfarbe</dt>
            <dd class="d-flex">
                <div class="border" style="height: 25px; width: 25px; background-color: {{ $vehicle->getTuningColor(1) }};"></div>
                <div class="border" style="height: 25px; width: 25px; background-color: {{ $vehicle->getTuningColor(2) }};"></div>
                <div class="border" style="height: 25px; width: 25px; background-color: {{ $vehicle->getTuningColor(3) }};"></div>
                <div class="border" style="height: 25px; width: 25px; background-color: {{ $vehicle->getTuningColor(4) }};"></div>
            </dd>
        </dl>
    </div>
</div>
