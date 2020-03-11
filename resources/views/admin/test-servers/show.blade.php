@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                Testserver Passwort
                            </div>

                            <div class="card-body">
                                <div class="input-group">
                                    <input type="text" name="password" id="password" class="form-control" readonly value="{{ $setting->Value }}" aria-label="Testserver Passwort">
                                    @if(auth()->user()->Rank >= 5)
                                    <div class="input-group-append">
                                        <a href="{{ route('admin.test-server.editPassword') }}" class="btn btn-primary">Ändern</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
