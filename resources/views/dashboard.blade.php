@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">
                @if (Session::get('role') == 'Admin')
                    <div class="col-lg-3 col-md-4 col-sm-4 grid-margin stretch-card">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <div class=" row">
                                    <div class="col-md-8">
                                        <h6 class="text-white mb-2">Generate Point Balance</h6>
                                        <div>
                                            <h3 class="text-white">
                                                {{ moneyFormatIndia(Session::get('creditPoint')) }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (Session::get('role') == 'Admin' || Session::get('role') == 'subadmin')
                    @if (Session::get('is_f') == 'false')
                        <div class="col-lg-3 col-md-4 col-sm-4 grid-margin stretch-card">
                            <div class="card bg-primary">
                                <a href="{{ url('/users') }}">
                                    <div class="card-body">
                                        <div class=" row">
                                            <div class="col-md-8">
                                                <h6 class="text-white mb-2">Agent Users</h6>
                                                <div>
                                                    <h3 class="text-white">{{ $data['users'] }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-1">
                                                <h1 class="text-white text-right mr-3"><i class="fa fa-users"></i></h2>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-3 col-md-4 col-sm-4 grid-margin stretch-card">
                            <div class="card bg-success">
                                <a href="{{ url('/users/Franchise') }}">
                                    <div class="card-body">
                                        <div class=" row">
                                            <div class="col-md-8">
                                                <h6 class="text-white mb-2">Franchise Users</h6>
                                                <div>
                                                    <h3 class="text-white">{{ $data['superDistributer'] }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-1">
                                                <h1 class="text-white text-right mr-3"><i class="fa fa-user"></i></h2>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card bg-danger">
                        <a href="{{ url('/blockedPlayers') }}">
                            <div class="card-body">
                                <div class=" row">
                                    <div class="col-md-8">
                                        <h6 class="text-white mb-2">Blocked Users</h6>
                                    </div>
                                    <div class="col-md-4 mt-1">
                                        <h1 class="text-white text-right mr-3"><i class="fa fa-user-times"></i></h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card bg-secondary">
                        <a href="{{ url('/transfer') }}">
                            <div class="card-body">
                                <div class=" row">
                                    <div class="col-md-8">
                                        <h6 class="text-white mb-2">Transfer Point</h6>
                                    </div>
                                    <div class="col-md-4 mt-1">
                                        <h1 class="text-white text-right mr-3"><i class="fa fa-exchange"></i></h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card bg-warning">
                        <a href="{{ url('/history') }}">
                            <div class="card-body">
                                <div class=" row">
                                    <div class="col-md-8">
                                        <h6 class="text-white mb-2">Player History</h6>
                                    </div>
                                    <div class="col-md-4 mt-1">
                                        <h1 class="text-white text-right mr-3"><i class="fa fa-dashboard"></i></h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card bg-success">
                        <a href="{{ url('/Tnover?type=7&from=' . date('Y-m-d') . '&to=' . date('Y-m-d')) }}">
                            <div class="card-body">
                                <div class=" row">
                                    <div class="col-md-8">
                                        <h6 class="text-white mb-2">TurnOver Result</h6>
                                    </div>
                                    <div class="col-md-4 mt-1">
                                        <h1 class="text-white text-right mr-3"><i class="fa fa-pie-chart"></i></h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card bg-danger">
                        <a href="{{ url('/transactions') }}">
                            <div class="card-body">
                                <div class=" row">
                                    <div class="col-md-8">
                                        <h6 class="text-white mb-2">Transaction Report</h6>
                                    </div>
                                    <div class="col-md-4 mt-1">
                                        <h1 class="text-white text-right mr-3"><i class="fa fa-bar-chart"></i></h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @if (Session::get('role') == 'Admin')
                    @if (Session::get('is_f') == 'true')
                        <div class="col-xl-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Franchises</h6>
                                    <canvas id="chartjsDoughnut"
                                        style="display: block; box-sizing: border-box; height: 150px; width: 744px;"
                                        width="744" height="372"></canvas>
                                </div>
                            </div>
                        </div>
                    @elseif (Session::get('is_f') == 'false')
                        <div class="col-xl-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Agents</h6>
                                    <canvas id="chartjsDoughnut1"
                                        style="display: block; box-sizing: border-box; height: 150px; width: 744px;"
                                        width="744" height="372"></canvas>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-xl-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Win Points & End Points Game wise</h6>
                                <canvas id="chartjsGroupedBar"
                                    style="display: block; box-sizing: border-box; height: 150px; width: 744px;" width="744"
                                    height="372"></canvas>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/chartjs.js') }}"></script> --}}
    <script src="{{ asset('assets/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/progressbar-js/progressbar.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script>
        new Chart($("#chartjsDoughnut"), {
            type: "doughnut",
            data: {
                labels: ["Premiums", "Executives", "Classics", "Players"],
                datasets: [{
                    label: "Population (millions)",
                    backgroundColor: ["#7ee5e5", "#f77eb9", "#4d8af0", "#ffc107"],
                    data: [{{ $chart_f }}],
                }, ],
            },
        });
        new Chart($("#chartjsDoughnut1"), {
            type: "doughnut",
            data: {
                labels: ["Agents", "Premiums", "Executives", "Classics", "Players"],
                datasets: [{
                    label: "Population (millions)",
                    backgroundColor: ["#7ee5e5", "#f77eb9", "#4d8af0", "#ffc107"],
                    data: [{{ $chart_a }}],
                }, ],
            },
        });
        new Chart($("#chartjsGroupedBar"), {
            type: "bar",
            data: {
                labels: ["Game40", "Game60", "Game Manule"],
                datasets: [{
                        label: "Win Points",
                        backgroundColor: "#f77eb9",
                        data: [{{ $chart_w }}],
                    },
                    {
                        label: "Play Points",
                        backgroundColor: "#7ee5e5",
                        data: [{{ $chart_p }}],
                    },
                ],
            },
        });
    </script>
@endpush
