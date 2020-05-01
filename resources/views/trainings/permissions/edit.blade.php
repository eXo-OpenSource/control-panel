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
                        <form method="POST" action="{{ route('trainings.permissions.update', [$type]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <table class="table table-sm table-responsive-sm">
                                <thead>
                                <tr>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Rang') }}</th>
                                    <th scope="col">{{ __('Recht') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($members as $member)
                                    <tr>
                                        <td>{{ $member['Name'] }}</td>
                                        <td>{{ $member['Rank'] }}</td>
                                        <td>
                                            <select class="form-control" name="permission[{{ $member['UserId'] }}]">
                                                <option value="0" @if($member['Permission'] === 0) selected="selected" @endif>keine</option>
                                                <option value="1" @if($member['Permission'] === 1) selected="selected" @endif>Ausbilden</option>
                                                <option value="2" @if($member['Permission'] === 2) selected="selected" @endif>Ausbilden und Inhalte pflegen</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="float-right">
                                <button type="submit" class="btn btn-primary">{{ __('Speichern') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
