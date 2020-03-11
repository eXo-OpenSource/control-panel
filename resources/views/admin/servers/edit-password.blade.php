@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Testserver Passwort ändern
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.server.updatePassword') }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label for="testPw">Passwort</label>
                                <input type="text" name="testPw" id="testPw" value="{{ old('testPw') ?? $setting->Value }}" class="form-control  @error('testPw') is-invalid @enderror">

                                @error('testPw')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Ändern</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
