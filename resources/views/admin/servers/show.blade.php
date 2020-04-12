@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            @if(auth()->user()->Rank >= 5)
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        Produktiver Server
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <form action="{{ route('admin.server.action') }}" method="POST" class="m-1">
                                                @csrf
                                                <input type="hidden" name="env" value="prod">
                                                <input type="hidden" name="action" value="start">
                                                <input type="submit" class="btn btn-success btn-lg"  value="Server starten">
                                            </form>
                                            <form action="{{ route('admin.server.action') }}" method="POST" class="m-1">
                                                @csrf
                                                <input type="hidden" name="env" value="prod">
                                                <input type="hidden" name="action" value="restart">
                                                <input type="submit" class="btn btn-warning btn-lg"  value="Server neustarten">
                                            </form>
                                            <form action="{{ route('admin.server.action') }}" method="POST" class="m-1">
                                                @csrf
                                                <input type="hidden" name="env" value="prod">
                                                <input type="hidden" name="action" value="stop">
                                                <input type="submit" class="btn btn-danger btn-lg"  value="Server stoppen">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        Test Server
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <form action="{{ route('admin.server.action') }}" method="POST" class="m-1">
                                                @csrf
                                                <input type="hidden" name="env" value="test">
                                                <input type="hidden" name="action" value="start">
                                                <input type="submit" class="btn btn-success btn-lg"  value="Server starten">
                                            </form>
                                            <form action="{{ route('admin.server.action') }}" method="POST" class="m-1">
                                                @csrf
                                                <input type="hidden" name="env" value="test">
                                                <input type="hidden" name="action" value="restart">
                                                <input type="submit" class="btn btn-warning btn-lg"  value="Server neustarten">
                                            </form>
                                            <form action="{{ route('admin.server.action') }}" method="POST" class="m-1">
                                                @csrf
                                                <input type="hidden" name="env" value="test">
                                                <input type="hidden" name="action" value="stop">
                                                <input type="submit" class="btn btn-danger btn-lg"  value="Server stoppen">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        Testserver Passwort
                                    </div>

                                    <div class="card-body">
                                        <div class="input-group">
                                            <input type="text" name="password" id="password" class="form-control" readonly value="{{ $setting->Value }}" aria-label="Testserver Passwort">

                                            <div class="input-group-append">
                                                @if(auth()->user()->Rank >= 5)
                                                    <a href="{{ route('admin.server.editPassword') }}" class="btn btn-primary">Ändern</a>
                                                @endif
                                                <a href="{{ 'mtasa://' . auth()->user()->Name . ':' . $setting->Value . '@' . env('WORKER_TEST_HOST_EXTERNAL') . ':22005' }}" class="btn btn-success">Join</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            @if(auth()->user()->Rank >= 5)
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        Produktiver Server
                                    </div>

                                    <div class="card-body">
                                        @if($logs !== null)
                                            <textarea wrap="soft" id="text" name="text" cols="120" rows="20" class="form-control w-100">
                                                {{ $logs->output }}
                                            </textarea>
                                        @else
                                            <div class="alert alert-danger" role="alert">Docker Container nicht erreichbar!</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        Test Server
                                    </div>

                                    <div class="card-body">
                                        @if($testLogs !== null)
                                            <textarea wrap="soft" id="text" name="text" cols="120" rows="20" class="form-control w-100">
                                            {{ $testLogs->output }}
                                        </textarea>
                                        @else
                                            <div class="alert alert-danger" role="alert">Docker Container nicht erreichbar!</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
