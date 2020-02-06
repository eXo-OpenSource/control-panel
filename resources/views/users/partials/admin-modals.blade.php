@if(auth()->user()->Rank >= 3)
    <button class="btn btn-danger" data-toggle="modal" data-target="#modal-ban">Ban</button>
    <button class="btn btn-danger" data-toggle="modal" data-target="#modal-kick">Kick</button>
    @if(auth()->user()->Rank >= 5)<button class="btn btn-danger" data-toggle="modal" data-target="#modal-unban">Unban</button>@endif

    <div class="modal fade" id="modal-kick" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __(':name kicken', ['name' => $user->Name]) }}</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <form method="POST" action="{{ route('admin.users.update', [$user->Id]) }}">
                    @method('PUT')
                    @csrf()
                    <input type="hidden" name="type" value="kick">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason">{{ __('Grund') }}</label>
                            <input class="form-control" id="reason" name="reason" type="text" placeholder="{{ __('Grund') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="submit">{{ __('Kicken') }}</button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ __('Abbrechen') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-ban" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __(':name bannen', ['name' => $user->Name]) }}</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <form method="POST" action="{{ route('admin.users.update', [$user->Id]) }}">
                    @method('PUT')
                    @csrf()
                    <input type="hidden" name="type" value="ban">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="duration">{{ __('Dauer') }}</label>
                            <div class="input-group">
                                <input class="form-control" id="duration" name="duration" type="text" placeholder="{{ __('Dauer') }}">
                                <div class="input-group-append"><span class="input-group-text">Stunden</span></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reason">{{ __('Grund') }}</label>
                            <input class="form-control" id="reason" name="reason" type="text" placeholder="{{ __('Grund') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="submit">{{ __('Bannen') }}</button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ __('Abbrechen') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(auth()->user()->Rank >= 5)
        <div class="modal fade" id="modal-unban" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __(':name entbannen', ['name' => $user->Name]) }}</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <form method="POST" action="{{ route('admin.users.update', [$user->Id]) }}">
                        @method('PUT')
                        @csrf()
                        <input type="hidden" name="type" value="unban">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="reason">{{ __('Grund') }}</label>
                                <input class="form-control" id="reason" name="reason" type="text" placeholder="{{ __('Grund') }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="submit">{{ __('Entbannen') }}</button>
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ __('Abbrechen') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endif
