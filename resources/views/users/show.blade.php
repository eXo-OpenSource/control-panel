@extends('layouts.app')

@section('content')

    <div class="w-full my-5 py-2 bg-red-500 sm:bg-blue-500 md:bg-gray-500 lg:bg-purple-500 xl:bg-green-500">
        <p class="block sm:hidden text-black">red - none</p>
        <p class="hidden sm:block md:hidden text-black">blue - sm</p>
        <p class="hidden md:block lg:hidden text-black">gray - md</p>
        <p class="hidden lg:block xl:hidden text-black">gray - lg</p>
        <p class="hidden xl:block text-black">green - xl</p>
    </div>
    <div class="flex items-center">
        <div class="w-full ml-2 mr-2 md:w-2/3 md:mx-auto">

            @if (session('status'))
                <div class="text-sm border border-t-8 rounded text-green-700 border-green-600 bg-green-100 px-3 py-4 mb-8" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="w-full break-words bg-white border border-2 rounded shadow-md mb-4">
                <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                    {{ $user->Name }}
                </div>
                <div class="p-6 flex xl:flex-row flex-col w-full">
                    <div class="flex flex-row justify-around xl:justify-start">
                        <div class="mr-6 hidden xl:block">
                            <img class="rounded shadow-lg" src="https://exo-reallife.de/images/skins/Skin{{ $user->character->Skin }}.jpg">
                        </div>
                        <dl class="user-stats mr-10">
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
                        <dl class="user-stats xl:mr-10">
                            <dt>Collectables</dt>
                            <dd>{{ $user->character->getCollectedCollectableCount() }}/40</dd>
                            <dt>GWD Note</dt>
                            <dd>{{ $user->character->PaNote }}</dd>
                            <dt>Fraktion</dt>
                            <dd>@if($user->character->hasFaction())<a href="{{ route('factions.show', [$user->character->FactionId]) }}">@endif{{ $user->character->getFactionName() }}@if($user->character->hasFaction())</a>@endif</dd>
                            <dt>Unternehmen</dt>
                            <dd>@if($user->character->hasCompany())<a href="{{ url('/') }}">@endif{{ $user->character->getCompanyName() }}@if($user->character->hasCompany())</a>@endif</dd>
                            <dt>Gruppe</dt>
                            <dd>@if($user->character->hasGroup())<a href="{{ url('/') }}">@endif{{ $user->character->getGroupName() }}@if($user->character->hasGroup())</a>@endif</dd>
                        </dl>
                        <div class="ml-6 block xl:hidden">
                            <img class="rounded shadow-lg" src="https://exo-reallife.de/images/skins/Skin{{ $user->character->Skin }}.jpg">
                        </div>
                    </div>
                    <div class="flex flex-row justify-around xl:justify-start mt-5 xl:mt-0">
                        <div>
                        <dl class="user-stats mr-10">
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
                        </dl></div>
                        <div>
                        <dl class="user-stats">
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
            <div class="w-full break-words bg-white border border-2 rounded shadow-md mb-4">
                <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                    Fahrzeuge
                </div>
                <div class="p-6 flex flex-col w-full">
                    @foreach($user->character->vehicles as $vehicle)
                        @if($loop->first)<div class="flex flex-row w-full">@endif
                        <div class="flex flex-col w-1/3">
                            <span class="text-2xl text-gray-900 font-light mb-2">{{ $vehicle->getName() }}</span>
                            <div class="flex">
                                <img src="https://exo-reallife.de/images/veh/Vehicle_{{ $vehicle->Model }}.jpg">
                                <dl class="vehicle-info">
                                    <dt>Kilometerstand</dt>
                                    <dd>{{ number_format($vehicle->Mileage / 1000, 2, ',', ' ') }} km</dd>
                                    <dt>Lackfarbe</dt>
                                    <dd class="flex">
                                        <div class="w-5 h-5 border border-black" style="background-color: {{ $vehicle->getTuningColor(1) }};"></div>
                                        <div class="w-5 h-5 border border-black ml-1" style="background-color: {{ $vehicle->getTuningColor(2) }};"></div>
                                        <div class="w-5 h-5 border border-black ml-1" style="background-color: {{ $vehicle->getTuningColor(3) }};"></div>
                                        <div class="w-5 h-5 border border-black ml-1" style="background-color: {{ $vehicle->getTuningColor(4) }};"></div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        @if(!$loop->last && $loop->iteration % 3 === 0)</div><div class="flex flex-row w-full mt-4">@endif
                        @if($loop->last)</div>@endif
                    @endforeach
                </div>
            </div>
            <div class="w-full break-words bg-white border border-2 rounded shadow-md mb-4">
                <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                    Spielerakte
                </div>
                <div class="p-6 flex xl:flex-row flex-col w-full">
                </div>
            </div>
        </div>
    </div>

@endsection
