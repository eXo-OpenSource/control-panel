@extends('layouts.app')

@section('title', __('Rechte') . ' - ' . __('Schulungen'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Rechte') }}
                    </div>
                    <div class="card-body">
                        @foreach($types as $type)
                        <a href="{{ route('trainings.permissions.edit', [$type['type']]) }}" class="btn btn-primary">{{ $type['name'] }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
