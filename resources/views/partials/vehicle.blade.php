<div class="col-md-2">
    <react-vehicle
        data-id="{{ $vehicle->Id }}"
        data-model="{{ $vehicle->Model }}"
        data-name="{{ $vehicle->getName() }}"
        data-premium="{{ $vehicle->Premium }}"
        data-distance="{{ number_format($vehicle->Mileage / 1000, 2, ',', ' ') }}"
        data-rare="{{ !$vehicle->isInShop() }}"
        data-unique="{{ $vehicle->isUnique() }}"
        data-ultra-rare="{{ $vehicle->isUltraRare() }}"
        data-premium-owner="{{ $vehicle->getPremiumOwner() }}"
        data-col1="{{ $vehicle->getTuningColor(1) }}"
        data-col2="{{ $vehicle->getTuningColor(2) }}"
        data-col3="{{ $vehicle->getTuningColor(3) }}"
        data-col4="{{ $vehicle->getTuningColor(4) }}">
    </react-vehicle>
</div>
