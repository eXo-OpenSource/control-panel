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
                    @can('privateData', $user)
                        <dt>Online-Status</dt>
                        <dd>@if ($user->isOnline()) <span class="badge badge-success">online</span> @else <span class="badge badge-danger">offline</span> @endif</dd>
                        <dt>Letzer Login</dt>
                        <dd>{{ $user->LastLogin->format('d.m.Y H:i:s') }}</dd>
                        <dt>Registrierungsdatum</dt>
                        <dd>{{ $user->RegisterDate->format('d.m.Y H:i:s') }}</dd>
                    @endcan
                    <dt>Karma</dt>
                    <dd>{{ $user->character->Karma }}</dd>
                    @can('privateData', $user)
                        <dt>Geld (Bar/Bank)</dt>
                        <dd>{{ number_format($user->character->Money, 0, ',', ' ') }}$ / {{ number_format($user->character->bank->Money, 0, ',', ' ') }}$</dd>
                    @endcan
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
                    @can('privateData', $user)
                        <dt>Collectables</dt>
                        <dd>{{ $user->character->getCollectedCollectableCount() }}/40</dd>
                    @endcan
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

    @can('privateData', $user)
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
    @endcan
</div>

@can('activity', $user)
    <div class="row">

        <div class="col-xl-6 col-lg-12">
            <react-chart data-chart="user:{{ $user->Id }}" data-state="true" data-title="{{ __('Aktivität') }}"></react-chart>
        </div>
    </div>
@endcan
