@section('title', __('Mods') . ' - '. $user->Name)
@can('mods', $user)
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Mods') }}
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-responsive-sm">
                                <thead>
                                <tr>
                                    <th scope="col">{{ __('Serial') }}</th>
                                    <th scope="col">{{ __('Model') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('MD5') }}</th>
                                    <th scope="col">{{ __('Erstmals gesehen') }}</th>
                                    <th scope="col">{{ __('Zuletzt gesehen') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user->getMods() as $mod)
                                    <tr>
                                        <td>{{ $mod->Serial }}</td>
                                        <td>{{ $mod->Model }}</td>
                                        <td>{{ $mod->Name }}</td>
                                        <td>{{ $mod->MD5 }}</td>
                                        <td>{{ $mod->CreatedAt }}</td>
                                        <td>{{ $mod->LastSeenAt }}</td>
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
