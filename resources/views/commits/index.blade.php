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

                <p class="h3 mb-4">{{ __('Letzten 100 Commits vom') }} {{ $allowedProjects[$selectedProject] }}</p>
                @foreach($commits as $commit)
                    <div class="card mb-3">
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-2 text-center">
                                    <img src="{{ $commit['avatar'] }}" style="width: 50px; height: 50px;" class="rounded" />
                                    <span class="d-block">{{ $commit['author'] }}</span>

                                </div>
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="badge badge-secondary">{{ $commit['branch'] }}</span> <small>{{ 'am' }} {{ \Carbon\Carbon::parse($commit['date'])->format('d.m.Y H:m') }}</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            {{ $commit['commit'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
