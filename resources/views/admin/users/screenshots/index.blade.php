@extends('layouts.app')

@section('content')


    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-start">
                <div>
                    <p class="h3">
                        {{ $user->Name }}
                        @if($user->isOnline())
                            <span class="badge badge-success">online</span>
                        @else
                            <span class="badge badge-danger">offline</span>
                        @endif
                    </p>
                </div>
                <div>
                    <a class="btn btn-primary" href="{{ route('admin.users.screenshots.store', [$user->Id]) }}" onclick="event.preventDefault(); document.getElementById('screenshot-form').submit();">{{ __('Screenshot anfordern') }}</a>
                    <form id="screenshot-form" action="{{ route('admin.users.screenshots.store', [$user->Id]) }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Multiaccounts') }}</div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-sm">
                            <thead>
                            <tr>
                                <th>{{ __('Admin') }}</th>
                                <th>{{ __('Datum') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Bild') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($user->screenshots()->with('admin')->orderBy('Id', 'DESC')->get() as $screenshot)
                                    <tr>
                                        <td>
                                            @if($screenshot->admin)<a href="{{ route('users.show', $screenshot->AdminId) }}">@endif
                                                {{ $screenshot->admin ? $screenshot->admin->Name : __('Unbekannt') }}
                                            @if($screenshot->admin)</a>@endif
                                        </td>
                                        <td>
                                            {{ $screenshot->CreatedAt }}
                                        </td>
                                        <td>
                                            {{ $screenshot->Status }}
                                        </td>
                                        <td>
                                            @if($screenshot->Status === 'Success')
                                            <img src="data:image/png;base64,{{ base64_encode(\Illuminate\Support\Facades\Storage::disk('screenshots')->get($screenshot->Image)) }}">
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
