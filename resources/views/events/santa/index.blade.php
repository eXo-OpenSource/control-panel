@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row mb-4">
                    <a href="{{ route('events.santa.create') }}" class="btn btn-primary ml-auto">Hochladen</a>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div style="flex: 0 0 400px;max-width: 400px;">
                <div class="">
                    <div class="santa">
                        @if(isset($head))<img src="{{ $head->Image }}">@endif
                        @if(isset($body))<img src="{{ $body->Image }}">@endif
                        @if(isset($legs))<img src="{{ $legs->Image }}">@endif
                    </div>
                </div>
        </div>
    </div>
@endsection

@section('head')
    <style>
        .drawing-area {
            background: pink;
        }
        .santa {
            display: flex;
            flex-direction: column;
            width: 400px;
            height: 600px;
            margin: 20px 0;
        }
        #head{
            height: 230px;
        }
        #body{
            height: 190px;
        }
        #legs{
            height: 180px;
        }

        body.body #body, body.legs #legs, body.head #head {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
@endsection

@section('script')
@endsection
