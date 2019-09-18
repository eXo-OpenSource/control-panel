@extends('layouts.app')

@section('content')
    <div class="flex items-center">
        <div class="w-full ml-2 mr-2 md:w-2/3 md:mx-auto">
            <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">

                <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                    Unternehmen
                </div>

                <table class="table w-full">
                    <tr>
                        <th>Name</th>
                        <th># Mitglieder</th>
                    </tr>
                    @foreach($companies as $company)
                        <tr>
                            <td><a href="{{ route('companies.show', [$company->Id]) }}">{{ $company->Name }}</a></td>
                            <td>{{ $company->membersCount() }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
