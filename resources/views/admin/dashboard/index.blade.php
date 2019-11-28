@extends('layouts.app')

@section('content')
    <div class="flex items-center flex-col">
        <div class="w-full ml-2 mr-2 mb-4 md:w-2/3 md:mx-auto">
            <ul class="flex">
                <li class="mr-3">
                    <a class="inline-block border border-blue-500 rounded py-1 px-3 bg-blue-500 text-white" href="{{ route('admin.user.search') }}">Benutzersuche</a>
                </li>
                <li class="mr-3">
                    <a class="inline-block border border-blue-500 rounded py-1 px-3 bg-blue-500 text-white" href="{{ route('admin.texture') }}">Texturen</a>
                </li>
            </ul>
        </div>

        <div class="w-full ml-2 mr-2 md:w-2/3 md:mx-auto">
            <div class="w-full break-words bg-white border border-2 rounded shadow-md mb-4">
                <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                    Tickets
                </div>
                <div class="p-6 flex xl:flex-row flex-col w-full">
                    <chart-component :chartdata="{{ json_encode($tickets) }}" :options="{{ json_encode(['scales' => ['yAxes' => [['ticks' => ['beginAtZero' => true, 'suggestedMax' => 8]]]]]) }}"></chart-component>
                </div>
            </div>
        </div>
    </div>
@endsection
