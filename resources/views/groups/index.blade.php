@extends('layouts.app')

@section('title', __('Gruppen'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Gruppen') }}
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('groups.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input class="form-control" id="name" name="name" autocomplete="off" type="text" placeholder="Name" value="{{ request()->get('name') }}">

                                        <select class="form-control" name="limit" id="limit">
                                            <option value="10" @if($limit == 10){{ 'selected' }}@endif>10</option>
                                            <option value="25" @if($limit == 25){{ 'selected' }}@endif>25</option>
                                            <option value="50" @if($limit == 50){{ 'selected' }}@endif>50</option>
                                            <option value="100" @if($limit == 100){{ 'selected' }}@endif>100</option>
                                            <option value="250" @if($limit == 250){{ 'selected' }}@endif>250</option>
                                            <option value="500" @if($limit == 500){{ 'selected' }}@endif>500</option>
                                        </select>

                                        <button type="submit" class="btn btn-sm btn-primary">{{ __('Absenden') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <table class="table table-sm table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col"><a href="{{ route('groups.index', ['sortBy' => 'name', 'direction' => $sortBy === 'name' && $direction === 'asc'  ? 'desc' : 'asc']) }}">{{ __('Name') }}</a></th>
                                <th scope="col"><a href="{{ route('groups.index', ['sortBy' => 'type', 'direction' => $sortBy === 'type' && $direction === 'asc'  ? 'desc' : 'asc']) }}">{{ __('Typ') }}</a></th>
                                <th scope="col"><a href="{{ route('groups.index', ['sortBy' => 'members', 'direction' => $sortBy === 'members' && $direction === 'asc'  ? 'desc' : 'asc']) }}">{{ __('# Mitglieder') }}</a></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($groups as $group)
                                <tr>
                                    <td><a href="{{ route('groups.show', [$group->Id]) }}">{{ $group->Name }}</a></td>
                                    <td>{{ $group->Type === 1 ? __('Gang') : __('Firma') }}</td>
                                    <td>{{ $group->members_count }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $groups->appends(['name' => request()->get('name'), 'limit' => $limit])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
