@extends('layouts.app')

@section('title', __('Gruppen'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('Gruppen') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('# Mitglieder') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($groups as $group)
                                <tr>
                                    <td><a href="{{ route('groups.show', [$group->Id]) }}">{{ $group->Name }}</a></td>
                                    <td>{{ $group->membersCount() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $groups->appends(['limit' => $limit])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
