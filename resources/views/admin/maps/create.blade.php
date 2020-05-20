@extends('layouts.app')

@section('title', __('Map hochladen') . ' - ' . __('Maps') . ' - ' . __('Admin'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                {{ __('Map hochladen') }}
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.maps.store') }}" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        <label for="name">{{ __('Name') }}</label>
                                        <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" name="name" value="{{ old('name') }}" >
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>

                                    <div class="custom-file mb-3">
                                        <input type="file" class="custom-file-input" id="map" name="map">
                                        <label class="custom-file-label{{ $errors->has('map') ? ' is-invalid' : '' }}" for="map">{{ __('Map auswählen') }}</label>
                                        @if ($errors->has('map'))
                                            <div class="invalid-feedback">{{ $errors->first('map') }}</div>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-primary float-right">{{ __('Hochladen') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
