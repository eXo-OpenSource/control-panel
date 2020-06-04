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
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2 text-center">
                                    <img src="{{ $commit['avatar'] }}" style="width: 50px; height: 50px;" class="rounded" />
                                    <p>{{ $commit['author'] }}</p>
                                </div>
                                <div class="col-10">
                                    <span class="float-right text-right">
                                        <span class="d-block">{{ \Carbon\Carbon::parse($commit['date'])->format('d.m.Y H:m') }}</span>
                                        <span class="badge badge-secondary">{{ $commit['branch'] }}</span>
                                    </span>
                                    {{ $commit['commit'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
