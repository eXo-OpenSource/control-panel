@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div class="flex flex-wrap justify-center">
            <div class="w-full max-w-sm">
                <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">

                    <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                        Fahrzeug-Textur Hochladen
                    </div>

                    <form class="w-full p-6" method="POST" action="{{ route('textures.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="block mb-4">
                            <label class="form-input-label" for="name">Name</label>
                            <input type="text" id="name" class="form-input mt-1 block w-full{{ $errors->has('name') ? ' border-red-500' : '' }}" placeholder="Namen der Textur" name="name" value="{{ old('name') }}" >
                            @if ($errors->has('name'))
                                <p class="form-input-error">
                                    {{ $errors->first('name') }}
                                </p>
                            @endif
                        </div>

                        <div class="block mb-4">
                            <label class="form-input-label" for="vehicle">Fahrzeugmodel</label>
                            <select class="form-select block w-full mt-1{{ $errors->has('vehicle') ? ' border-red-500' : '' }}" name="vehicle" id="vehicle">
                                <option value="">[Model auswählen]</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle['Id'] }}" @if($vehicle['Id'] == old('vehicle')){{ 'selected' }}@endif>{{ $vehicle['Name'] }} (ID: {{ $vehicle['Id'] }})</option>
                                @endforeach
                            </select>
                            @if ($errors->has('vehicle'))
                                <p class="form-input-error">
                                    {{ $errors->first('vehicle') }}
                                </p>
                            @endif
                        </div>

                        <div class="block mb-4">
                            <label class="form-input-label">Typ</label>
                            <div>
                                <div class="inline-flex items-center">
                                    <input type="radio" class="form-radio" id="private" name="type" value="0" @if(old('type') === '0'){{ 'checked' }}@endif>
                                    <label class="ml-2" for="private">Privat nur für mich</label>
                                </div>
                            </div>
                            <div>
                                <div class="inline-flex items-center">
                                    <input type="radio" class="form-radio" id="public" name="type" value="1" @if(old('type') === '1'){{ 'checked' }}@endif>
                                    <label class="ml-2" for="public">Öffentlich</label>
                                </div>
                            </div>
                            @if ($errors->has('type'))
                                <p class="form-input-error">
                                    {{ $errors->first('type') }}
                                </p>
                            @endif
                        </div>

                        <div class="block mb-4">
                            <label class="form-input-label" for="texture">Textur</label>
                            <input type="file" id="texture" class="mt-1 block w-full{{ $errors->has('file') ? ' border-red-500' : '' }}" name="texture">
                            @if ($errors->has('texture'))
                                <p class="form-input-error">
                                    {{ $errors->first('texture') }}
                                </p>
                            @endif
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary float-right">
                                Hochladen
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
