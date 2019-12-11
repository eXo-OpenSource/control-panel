@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="drawing-area">
                    <div class="santa">
                        @if(isset($head))<img src="{{ $head->Image }}">@endif
                        @if(isset($body))<img src="{{ $body->Image }}">@endif
                        @if(isset($legs))<img src="{{ $legs->Image }}">@endif
                        <canvas id="head" resize></canvas>
                        <canvas id="body" resize></canvas>
                        <canvas id="legs" resize></canvas>
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
