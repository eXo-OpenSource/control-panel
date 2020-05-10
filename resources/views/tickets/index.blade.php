@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <react-tickets data-minimal="{{ request()->exists('minimal') }}"></react-tickets>
            </div>
        </div>
    </div>
@endsection
