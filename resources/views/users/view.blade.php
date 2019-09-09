@extends('layouts.app')

@section('content')
    <div class="flex items-center">
        <div class="md:w-1/2 md:mx-auto">

            @if (session('status'))
                <div class="text-sm border border-t-8 rounded text-green-700 border-green-600 bg-green-100 px-3 py-4 mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">

                <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                    {{ $user->Name }}
                </div>

                <div class="w-full p-6">
                    <div>
                        <table>
                            <tr class="w-10">
                                <td >Geld</td>
                                <td>{{ $user->character->Money }} / {{ $user->character->bankAccount->Money }} $</td>
                            </tr>
                            <tr>
                                <td>PlayTime</td>
                                <td>{{ $user->character->PlayTime / 60 }}</td>
                            </tr>
                            <tr>
                                <td>Karma</td>
                                <td>{{ $user->character->Karma }}</td>
                            </tr>
                            <tr>
                                <td>Collectables</td>
                                <td>{{ $user->character->getCollectedCollectableCount() }}/40</td>
                            </tr>
                            <tr>
                                <td>PaNote</td>
                                <td>{{ $user->character->PaNote }}</td>
                            </tr>
                        </table>
                        <table>
                            <tr>
                                <td>WeaponLevel</td>
                                <td>{{ $user->character->WeaponLevel }}</td>
                            </tr>
                            <tr>
                                <td>VehicleLevel</td>
                                <td>{{ $user->character->VehicleLevel }}</td>
                            </tr>
                            <tr>
                                <td>SkinLevel</td>
                                <td>{{ $user->character->SkinLevel }}</td>
                            </tr>
                            <tr>
                                <td>JobLevel</td>
                                <td>{{ $user->character->JobLevel }}</td>
                            </tr>
                        </table>
                        <table>
                            <tr>
                                <td>HasPilotsLicense</td>
                                <td>{{ $user->character->HasPilotsLicense }}</td>
                            </tr>
                            <tr>
                                <td>HasDrivingLicense</td>
                                <td>{{ $user->character->HasDrivingLicense }}</td>
                            </tr>
                            <tr>
                                <td>HasBikeLicense</td>
                                <td>{{ $user->character->HasBikeLicense }}</td>
                            </tr>
                            <tr>
                                <td>HasTruckLicense</td>
                                <td>{{ $user->character->HasTruckLicense }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
