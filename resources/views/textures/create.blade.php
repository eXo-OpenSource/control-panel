@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div class="flex flex-wrap justify-center">
            <div class="w-full max-w-sm">
                <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">

                    <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                        Fahrzeug-Textur Hochladen
                    </div>

                    <form class="w-full p-6" method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="block mb-4">
                            <label class="form-input-label" for="name">Name</label>
                            <input type="text" id="name" class="form-input mt-1 block w-full{{ $errors->has('name') ? ' border-red-500' : '' }}" placeholder="Namen der Textur" name="name" value="{{ old('name') }}" required>
                            @if ($errors->has('name'))
                                <p class="form-input-error">
                                    {{ $errors->first('name') }}
                                </p>
                            @endif
                        </div>

                        <div class="block mb-4">
                            <label class="form-input-label" for="vehicle">Fahrzeugmodel</label>
                            <select class="form-select block w-full mt-1{{ $errors->has('vehicle') ? ' border-red-500' : '' }}" name="vehicle" id="vehicle">
                                <option value="0">[Model auswählen]</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle['Id'] }}">{{ $vehicle['Name'] }} (ID: {{ $vehicle['Id'] }})</option>
                                @endforeach
                            </select>
                            @if ($errors->has('public'))
                                <p class="form-input-error">
                                    {{ $errors->first('public') }}
                                </p>
                            @endif
                        </div>

                        <div class="block mb-4">
                            <label class="form-input-label">Typ</label>
                            <div>
                                <div class="inline-flex items-center">
                                    <input type="radio" class="form-radio" id="private" name="public" value="0">
                                    <label class="ml-2" for="private">Privat nur für mich</label>
                                </div>
                            </div>
                            <div>
                                <div class="inline-flex items-center">
                                    <input type="radio" class="form-radio" id="public" name="public" value="1">
                                    <label class="ml-2" for="public">Öffentlich</label>
                                </div>
                            </div>
                            @if ($errors->has('public'))
                                <p class="form-input-error">
                                    {{ $errors->first('public') }}
                                </p>
                            @endif
                        </div>

                        <div class="block mb-4">
                            <label class="form-input-label" for="file">Textur</label>
                            <input type="file" id="file" class="mt-1 block w-full{{ $errors->has('name') ? ' border-red-500' : '' }}" name="file" required>
                            @if ($errors->has('name'))
                                <p class="form-input-error">
                                    {{ $errors->first('name') }}
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
