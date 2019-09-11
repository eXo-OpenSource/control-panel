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
                    <div class="p-6">
                        <table class="table w-full">
                            <tr class="mb-2">
                                <td>Letzer Login</td>
                                <td>{{ $user->LastLogin->format('d.m.Y H:i:s') }}</td>
                            </tr>
                            <tr class="mb-2">
                                <td>Registrierungsdatum</td>
                                <td>{{ $user->RegisterDate->format('d.m.Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td>Karma</td>
                                <td>{{ $user->character->Karma }}</td>
                            </tr>
                            <tr>
                                <td >Geld (Bar/Bank)</td>
                                <td>{{ $user->character->Money }}$ / {{ $user->character->bankAccount->Money }}$</td>
                            </tr>
                            <tr>
                                <td>Spielzeit</td>
                                <td>{{ $user->character->PlayTime / 60 }}</td>
                            </tr>
                            <tr>
                                <td>Collectables</td>
                                <td>{{ $user->character->getCollectedCollectableCount() }}/40</td>
                            </tr>
                            <tr>
                                <td>GWD Note</td>
                                <td>{{ $user->character->PaNote }}</td>
                            </tr>
                            <tr>
                                <td>Fraktion</td>
                                <td>{{ $user->character->FactionId }}</td>
                            </tr>
                            <tr>
                                <td>Unternehmen</td>
                                <td>{{ $user->character->CompanyId }}</td>
                            </tr>
                            <tr>
                                <td>Gruppe</td>
                                <td>{{ $user->character->GroupId }}</td>
                            </tr>
                        </table>
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
                                    <td>Fischenlevel</td>
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
                                    <td>{{ $user->character->HasDrivingLicense }}</td>
                                </tr>
                                <tr>
                                    <td>Motorradführerschein</td>
                                    <td>{{ $user->character->HasBikeLicense }}</td>
                                </tr>
                                <tr>
                                    <td>LKW-Führerschein</td>
                                    <td>{{ $user->character->HasTruckLicense }}</td>
                                </tr>
                                <tr>
                                    <td>Flugschein</td>
                                    <td>{{ $user->character->HasPilotsLicense }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
