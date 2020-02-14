@extends('layouts.app')

@section('content')


    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Multiaccounts') }}</div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-sm">
                            <thead>
                            <tr>
                                <th>{{ __('Serial') }}</th>
                                <th>{{ __('Spieler') }}</th>
                                <th>{{ __('Admin') }}</th>
                                <th>{{ __('angelegt am') }}</th>
                                <th>{{ __('Registrierung erlaubt') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($multiaccounts as $multiaccount)
                                <tr>
                                    <td>{{ $multiaccount->Serial }}</td>
                                    <td>
                                        @foreach ($multiaccount->getUsers() as $user)
                                            @if($user)
                                                {{$user->Name}},
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($multiaccount->admin)
                                            {{ $multiaccount->admin->Name }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($multiaccount->Timestamp > 0)
                                            {{ date('d.m.Y H:i', $multiaccount->Timestamp) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($multiaccount->allowCreate == 1)
                                            <span class="badge badge-success">Ja</span>
                                        @else
                                            <span class="badge badge-danger">Nein</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $multiaccounts->links() }}
            </div>
        </div>
    </div>
@endsection
