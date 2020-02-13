@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('Wer ist online') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-responsive">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Fraktion') }}</th>
                                <th scope="col">{{ __('Unternehmen') }}</th>
                                <th scope="col">{{ __('Gang/Firma') }}</th>
                                <th scope="col">{{ __('Spielzeit') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data->Players as $player)
                                <tr>
                                    <td>@if($player->Id != -1)<a href="{{ route('users.show', [$player->Id]) }}">{{ $player->Name }}@else{{ $player->Name }}@endif</a></td>
                                    <td>@if($player->FactionId != 0)<a href="{{ route('factions.show', [$player->FactionId]) }}">{{ $player->FactionName }}</a>@else{{ $player->FactionName }}@endif</td>
                                    <td>@if($player->CompanyId != 0)<a href="{{ route('companies.show', [$player->CompanyId]) }}">{{ $player->CompanyName }}</a>@else{{ $player->CompanyName }}@endif</td>
                                    <td>@if($player->GroupId != 0)<a href="{{ route('groups.show', [$player->GroupId]) }}">{{ $player->GroupName }}</a>@else{{ $player->GroupName }}@endif</td>
                                    <td>@playTime($player->PlayTime)</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        {{ __('Derzeit sind :count Spieler online', ['count' => $data->Total]) }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    @foreach($data->Factions as $faction)
                                        <div class="col-md-4 col-xs-6">
                                            <span>{{ $faction->Name }}: {{ $faction->Count }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    @foreach($data->Companies as $company)
                                        <div class="col-md-4 col-xs-6">
                                            <span>{{ $company->Name }}: {{ $company->Count }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
