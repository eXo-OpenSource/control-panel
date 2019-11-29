@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row mb-4 d-flex justify-content-between align-items-start">
                    <div></div>
                    <div>
                        <a class="btn btn-danger" href="{{ route('admin.user.search') }}">Ban</a>
                        <a class="btn btn-danger" href="{{ route('admin.texture') }}">Kick</a>
                        <a class="btn btn-danger" href="{{ route('admin.texture') }}">Unban</a>
                    </div>
                </div>
                <div class="row">
                    <div class="" style="height: 270px; width: 134px; padding-right: 15px; padding-left: 15px; position: relative;">
                        <div class="card" style="height: 270px; width: 104px; background-image: url('https://exo-reallife.de/images/skins/Skin{{ $user->character->Skin }}.jpg'); background-repeat: no-repeat; background-size: cover; background-position-x: center">

                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title mb-0">{{ $user->Name }}</h4>
                                    </div>
                                </div>
                                <dl class="user-stats mt-2">
                                    <dt>Letzer Login</dt>
                                    <dd>{{ $user->LastLogin->format('d.m.Y H:i:s') }}</dd>
                                    <dt>Registrierungsdatum</dt>
                                    <dd>{{ $user->RegisterDate->format('d.m.Y H:i:s') }}</dd>
                                    <dt>Karma</dt>
                                    <dd>{{ $user->character->Karma }}</dd>
                                    <dt>Geld (Bar/Bank)</dt>
                                    <dd>{{ number_format($user->character->Money, 0, ',', ' ') }}$ / {{ number_format($user->character->bankAccount->Money, 0, ',', ' ') }}$</dd>
                                    <dt>Spielzeit</dt>
                                    <dd>{{ $user->character->getPlayTime() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <dl class="user-stats">
                                    <dt>Collectables</dt>
                                    <dd>{{ $user->character->getCollectedCollectableCount() }}/40</dd>
                                    <dt>GWD Note</dt>
                                    <dd>{{ $user->character->PaNote }}</dd>
                                    <dt>Fraktion</dt>
                                    <dd>@if($user->character->hasFaction())<a href="{{ route('factions.show', [$user->character->FactionId]) }}">@endif{{ $user->character->getFactionName() }}@if($user->character->hasFaction())</a>@endif</dd>
                                    <dt>Unternehmen</dt>
                                    <dd>@if($user->character->hasCompany())<a href="{{ route('companies.show', [$user->character->CompanyId]) }}">@endif{{ $user->character->getCompanyName() }}@if($user->character->hasCompany())</a>@endif</dd>
                                    <dt>Gruppe</dt>
                                    <dd>@if($user->character->hasGroup())<a href="{{ route('groups.show', [$user->character->GroupId])  }}">@endif{{ $user->character->getGroupName() }}@if($user->character->hasGroup())</a>@endif</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <dl class="user-stats">
                                    <dt>Waffenlevel</dt>
                                    <dd>{{ $user->character->WeaponLevel }}</dd>
                                    <dt>Fahrzeuglevel</dt>
                                    <dd>{{ $user->character->VehicleLevel }}</dd>
                                    <dt>Skinlevel</dt>
                                    <dd>{{ $user->character->SkinLevel }}</dd>
                                    <dt>Joblevel</dt>
                                    <dd>{{ $user->character->JobLevel }}</dd>
                                    <dt>Fischerlevel</dt>
                                    <dd>{{ $user->character->FishingLevel }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <dl class="user-stats">
                                    <dt>Gebannt</dt>
                                    <dd>@if($banned === false)<i class="fas fa-times text-green-500"></i>@else @if($banned === 0)<i class="fas fa-check text-red-500"></i>@else{{ (new \DateTime)->setTimestamp($banned)->format('d.m.Y H:i:s') }}@endif @endif</dd>
                                    <dt>Autoführerschein</dt>
                                    <dd>@if($user->character->HasDrivingLicense === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</dd>
                                    <dt>Motorradführerschein</dt>
                                    <dd>@if($user->character->HasBikeLicense === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</dd>
                                    <dt>LKW-Führerschein</dt>
                                    <dd>@if($user->character->HasTruckLicense === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</dd>
                                    <dt>Flugschein</dt>
                                    <dd>@if($user->character->HasPilotsLicense === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</dd>
                                </dl>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <dl class="user-stats">
                                    <dt>Gebannt</dt>
                                    <dd>@if($banned === false)<i class="fas fa-times text-green-500"></i>@else @if($banned === 0)<i class="fas fa-check text-red-500"></i>@else{{ (new \DateTime)->setTimestamp($banned)->format('d.m.Y H:i:s') }}@endif @endif</dd>
                                    <dt>Autoführerschein</dt>
                                    <dd>@if($user->character->HasDrivingLicense === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</dd>
                                    <dt>Motorradführerschein</dt>
                                    <dd>@if($user->character->HasBikeLicense === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</dd>
                                    <dt>LKW-Führerschein</dt>
                                    <dd>@if($user->character->HasTruckLicense === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</dd>
                                    <dt>Flugschein</dt>
                                    <dd>@if($user->character->HasPilotsLicense === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($user->character->vehicles as $vehicle)
                <div class="col-md-2">
                    <div class="card">
                        <img class="bd-placeholder-img card-img-top" src="https://exo-reallife.de/images/veh/Vehicle_{{ $vehicle->Model }}.jpg">
                        <div class="card-body">
                            <h5 class="card-title">{{ $vehicle->getName() }}</h5>
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
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Spielerakte') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Fraktion/Unternehmen') }}</th>
                                <th scope="col">{{ __('Dauer') }}</th>
                                <th scope="col">{{ __('Uninviter') }}</th>
                                <th scope="col">{{ __('Grund') }}</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user->character->history as $history)
                                <tr>
                                    <td>{{ $history->element->Name }}</td>
                                    <td>{{ $history->getDuration() }}</td>
                                    <td>{{ $history->getUninviter() }}</td>
                                    <td>{{ $history->ExternalReason }}</td>
                                    <td><button class="btn btn-primary">Details</button></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--
    <div class="w-full my-5 py-2 bg-red-500 sm:bg-blue-500 md:bg-gray-500 lg:bg-purple-500 xl:bg-green-500">
        <p class="block sm:hidden text-black">red - none</p>
        <p class="hidden sm:block md:hidden text-black">blue - sm</p>
        <p class="hidden md:block lg:hidden text-black">gray - md</p>
        <p class="hidden lg:block xl:hidden text-black">gray - lg</p>
        <p class="hidden xl:block text-black">green - xl</p>
    </div>
    -->

    <div class="flex flex-col">
        <div class="flex items-center">
            <div class="w-full ml-2 mr-2 md:w-2/3 md:mx-auto">

                <div class="w-full break-words bg-white border border-2 rounded shadow-md mb-4">
                    <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                        Aktivität
                    </div>
                    <div class="p-6 flex xl:flex-row flex-col w-full">
                        <chart-component :chartdata="{{ json_encode($user->character->getActivity(true)) }}" :options="{{ json_encode(['scales' => ['yAxes' => [['ticks' => ['beginAtZero' => true, 'stepSize' => 1, 'suggestedMax' => 8]]]]]) }}"></chart-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
