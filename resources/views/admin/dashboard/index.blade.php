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
                    <div class="col-sm-6 col-lg-3">
                        <div class="card text-white bg-info" style="background: linear-gradient(45deg,#39f 0%,#2982cc 100%);">
                            <div class="card-body pb-0">
                                <div class="text-value-lg">{{ $lastPlayerCount }}</div>
                                <div>Players</div>
                            </div>
                            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                                <canvas class="chart" id="card-chart2" height="70"></canvas>
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
    <link href="https://unpkg.com/@coreui/coreui-chartjs@2.0.0-beta.0/dist/css/coreui-chartjs.css" rel="stylesheet">
    <script src="https://unpkg.com/@coreui/coreui-chartjs@2.0.0-beta.0/dist/js/coreui-chartjs.bundle.js"></script>
    <script>

        Chart.defaults.global.pointHitDetectionRadius = 1;
        Chart.defaults.global.tooltips.enabled = false;
        Chart.defaults.global.tooltips.mode = 'index';
        Chart.defaults.global.tooltips.position = 'nearest';
        Chart.defaults.global.tooltips.custom = coreui.ChartJS.customTooltips;

        var data = {!! json_encode($factionData) !!};

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



        var playerCountData = {!! json_encode($playerCountData) !!};

        const cardChart2 = new Chart(document.getElementById('card-chart2'), {
            type: 'line',
            data: {
                labels: playerCountData.labels,
                datasets: playerCountData.datasets, /*[
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
                            display: false
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
    </script>
@endsection

