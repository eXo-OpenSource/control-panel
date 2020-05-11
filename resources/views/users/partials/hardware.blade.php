@section('title', __('Hardware') . ' - '. $user->Name)
@can('hardware', $user)
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Hardware') }}
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-responsive-sm">
                                <thead>
                                <tr>
                                    <th scope="col">{{ __('Serial') }}</th>
                                    <th scope="col">{{ __('GPU') }}</th>
                                    <th scope="col">{{ __('VRAM') }}</th>
                                    <th scope="col">{{ __('Auflösung') }}</th>
                                    <th scope="col">{{ __('Vollbild/Fenster') }}</th>
                                    <th scope="col">{{ __('Screenshots') }}</th>
                                    <th scope="col">{{ __('FPS nach Login') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user->hardware as $hardware)
                                    <tr>
                                        <td>{{ $hardware->Serial }}</td>
                                        <td>{{ $hardware->GPU }}</td>
                                        <td>{{ $hardware->FreeVRAM === null ? '???' : $hardware->VRAM - $hardware->FreeVRAM }}/{{ $hardware->VRAM }} MB</td>
                                        <td>{{ $hardware->Resolution }}</td>
                                        <td>{{ $hardware->Window === 0 ? __('Vollbild') : __('Fenstermodus') }}</td>
                                        <td>{{ $hardware->Resolution === 0 ? __('Blockiert') : __('Erlaubt') }}</td>
                                        <td>{{ $hardware->FPS }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcan
