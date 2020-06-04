@extends('layouts.app')

@section('title', __('Commits'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <div class="btn-group mb-4" role="group">
                    @foreach($allowedProjects as $project => $name)
                        <a class="btn btn-ghost-primary @if($project === $selectedProject){{'active'}}@endif" href="{{ route('commits', ['project' => $project]) }}">{{ $name }}</a>
                    @endforeach
                </div>

                <p class="h3">{{ __('Letzten 100 Commits vom') }} {{ $allowedProjects[$selectedProject] }}</p>
                @foreach($commits as $commit)
                    <div class="card">
                        <div class="card-header">
                            {{ $commit['author'] }} {{ __('am') }} {{ $commit['date']->format('d.m.Y H:m') }}
                            <span class="float-right badge badge-secondary">{{ $commit['branch'] }}</span>
                        </div>
                        <div class="card-body">
                            {{ $commit['commit'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
