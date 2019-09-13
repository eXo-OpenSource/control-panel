@extends('layouts.app')

@section('content')
    <div class="flex items-center">
        <div class="w-full ml-2 mr-2 md:w-2/3 md:mx-auto">

            @if (session('status'))
                <div class="text-sm border border-t-8 rounded text-green-700 border-green-600 bg-green-100 px-3 py-4 mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex md:flex-row flex-col w-full">
                <div class="w-full md:w-2/3 break-words bg-white border border-2 rounded shadow-md mr-2">
                    <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                        {{ $user->Name }}
                    </div>
                    <div class="p-6 flex">
                        <div class="mr-6">
                            <img class="rounded shadow-lg" src="https://exo-reallife.de/images/skins/Skin{{ $user->character->Skin }}.jpg">
                        </div>
                        <dl class="user-stats mr-6">
                            <dt>Letzer Login</dt>
                            <dd>{{ $user->LastLogin->format('d.m.Y H:i:s') }}</dd>
                            <dt>Registrierungsdatum</dt>
                            <dd>{{ $user->RegisterDate->format('d.m.Y H:i:s') }}</dd>
                            <dt>Karma</dt>
                            <dd>{{ $user->character->Karma }}</dd>
                            <dt>Geld (Bar/Bank)</dt>
                            <dd>{{ $user->character->Money }}$ / {{ $user->character->bankAccount->Money }}$</dd>
                            <dt>Spielzeit</dt>
                            <dd>{{ $user->character->getPlayTime() }}</dd>
                        </dl>
                        <dl class="user-stats">
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
                    </div>
                </div>
                <div class="flex-row w-full md:w-1/3">
                    <div class="break-words bg-white border border-2 rounded shadow-md mt-2 md:mt-0">
                        <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                            Levels
                        </div>
                        <div class="p-6">
                            <table class="table w-full">
                                <tr>
                                    <td>Waffenlevel</td>
                                    <td>{{ $user->character->WeaponLevel }}</td>
                                </tr>
                                <tr>
                                    <td>Fahrzeuglevel</td>
                                    <td>{{ $user->character->VehicleLevel }}</td>
                                </tr>
                                <tr>
                                    <td>Skinlevel</td>
                                    <td>{{ $user->character->SkinLevel }}</td>
                                </tr>
                                <tr>
                                    <td>Joblevel</td>
                                    <td>{{ $user->character->JobLevel }}</td>
                                </tr>
                                <tr>
                                    <td>Fischerlevel</td>
                                    <td>{{ $user->character->FishingLevel }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="break-words bg-white border border-2 rounded shadow-md mt-2">
                        <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                            Scheine
                        </div>
                        <div class="p-6">
                            <table class="table w-full">
                                <tr>
                                    <td>Autoführerschein</td>
                                    <td>@if($user->character->HasDrivingLicense === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</td>
                                </tr>
                                <tr>
                                    <td>Motorradführerschein</td>
                                    <td>@if($user->character->HasBikeLicense === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</td>
                                </tr>
                                <tr>
                                    <td>LKW-Führerschein</td>
                                    <td>@if($user->character->HasTruckLicense === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</td>
                                </tr>
                                <tr>
                                    <td>Flugschein</td>
                                    <td>@if($user->character->HasPilotsLicense === 1)<i class="fas fa-check text-green-500"></i>@else<i class="fas fa-times text-red-500"></i>@endif</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
