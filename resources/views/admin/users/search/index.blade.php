@extends('layouts.app')

@section('content')
    <div class="flex items-center">
        <div class="w-full ml-2 mr-2 md:w-2/3 md:mx-auto">
            <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">

                <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                    Benutzersuche
                </div>

                <form class="flex flex-row" method="GET" action="{{ route('admin.user.search') }}">
                    <input type="text" id="name" class="p-3 border block w-full{{ $errors->has('password') ? ' border-red-500' : '' }}" value="{{ request()->get('name') }}" placeholder="Name" name="name" autocomplete="off">

                    <button type="submit" class="cursor-pointer px-5 py-2 inline-block bg-blue-600 text-blue-100 hover:bg-blue-500 hover:text-white">
                        {{ __('Absenden') }}
                    </button>
                    <!--  -->
                    <select class="form-select rounded-none block" name="limit" id="limit">
                        <option value="10" @if($limit == 10){{ 'selected' }}@endif>10</option>
                        <option value="25" @if($limit == 25){{ 'selected' }}@endif>25</option>
                        <option value="50" @if($limit == 50){{ 'selected' }}@endif>50</option>
                        <option value="100" @if($limit == 100){{ 'selected' }}@endif>100</option>
                        <option value="250" @if($limit == 250){{ 'selected' }}@endif>250</option>
                        <option value="500" @if($limit == 500){{ 'selected' }}@endif>500</option>
                    </select>
                </form>

                <table class="table table-sm w-full">
                    <tr>
                        <th>Name</th>
                        <th>Spielzeit</th>
                        <th>Letzter Login</th>
                        <th>Letzte IP</th>
                        <th>Letzte Serial</th>
                    </tr>
                    @foreach($users as $user)
                    <tr onclick="location.href = '{{ route('users.show', [$user->Id]) }}';" class="cursor-pointer">
                        <td>{{ $user->Name }}</td>
                        <td>{{ $user->character->getPlayTime() }}</td>
                        <td>{{ $user->LastLogin->format('d.m.Y H:i:s') }}</td>
                        <td>{{ $user->LastIP }}</td>
                        <td>{{ $user->LastSerial }}</td>
                    </tr>
                    @endforeach
                </table>

                <div class="my-2">
                    {{ $users->appends(['name' => request()->get('name'), 'limit' => $limit])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
