@extends('layouts.app')

@section('content')
    <div class="flex items-center">
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
