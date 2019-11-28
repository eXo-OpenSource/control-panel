@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="d-block mb-4">
                    <a class="btn btn-primary" href="{{ route('admin.user.search') }}">Benutzersuche</a>
                    <a class="btn btn-primary" href="{{ route('admin.texture') }}">Texturen</a>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card text-white bg-primary" style="background: linear-gradient(45deg,#321fdb 0%,#1f1498 100%);">
                            <div class="card-body pb-0">
                                <div class="text-value-lg">{{ $totalTickets }}</div>
                                <div># Tickets</div>
                            </div>
                            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                                <canvas class="chart" id="card-chart1" height="70"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mb-0">Aktivität der Fraktionen</h4>
                            </div>
                        </div>

                    <div class="c-chart-wrapper" style="height:300px;margin-top:40px;">
                        <canvas id="canvas-1" height="300" style="display: block;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        const random = () => Math.round(Math.random() * 100)

        var data = {!! json_encode($data) !!};

        const lineChart = new Chart(document.getElementById('canvas-1'), {
            type: 'line',
            data: {
                labels : data.labels,
                datasets : data.datasets
            },
            options: {
                responsive: true
            }
        })

        var ticketData = {!! json_encode($tickets) !!};

        const cardChart1 = new Chart(document.getElementById('card-chart1'), {
            type: 'line',
            data: {
                labels: ticketData.labels,
                datasets: ticketData.datasets, /*[
                    {
                        label: 'My First dataset',
                        backgroundColor: 'transparent',
                        borderColor: 'rgba(255,255,255,.55)',
                        pointBackgroundColor: getStyle('--primary'),
                        data:
                    }
                ]*/
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            color: 'transparent',
                            zeroLineColor: 'transparent'
                        },
                        ticks: {
                            fontSize: 2,
                            fontColor: 'transparent'
                        }
                    }],
                    yAxes: [{
                        display: false,
                        ticks: {
                            display: false,
                            min: 0,
                            max: 40
                        }
                    }]
                },
                elements: {
                    line: {
                        borderWidth: 1
                    },
                    point: {
                        radius: 4,
                        hitRadius: 10,
                        hoverRadius: 4
                    }
                }
            }
        })
        /*
        var lineChart = new Chart($('#canvas-1'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Aktivität in h',
                    backgroundColor: 'rgba(220, 220, 220, 0.2)',
                    borderColor: 'rgba(220, 220, 220, 1)',
                    pointBackgroundColor: 'rgba(220, 220, 220, 1)',
                    pointBorderColor: '#fff',
                    data: data.data
                }]
            },
            options: {
                scales: {
                    yAxes: [
                        {ticks: {beginAtZero: true, suggestedMax: 8}}
                    ]
                },
                responsive: true
            }
        });*/
    </script>
@endsection

