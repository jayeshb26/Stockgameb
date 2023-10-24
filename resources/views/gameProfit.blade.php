@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

    {{-- <nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Forms</a></li>
    <li class="breadcrumb-item active" aria-current="page">Basic Elements</li>
  </ol>
</nav> --}}

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header d-flex">
                    <div class="col-sm-8 mt-2">
                        <div class="btn-group">
                            @php
                                $mon = strtotime('last monday');
                                $monday = date('W', $mon) == date('W') ? $mon - 7 * 86400 : $mon;
                                $sunday = strtotime(date('Y-m-d', $monday) . ' +6 days');
                                $week_sd = date('Y-m-d', $monday);
                                $week_ed = date('Y-m-d', $sunday);
                                $checkmonday = date('l') == 'Monday' ? date('Y-m-d') : date('Y-m-d', strtotime('last monday'));
                                // die();
                            @endphp
                            @if (Request::segment(2) == 'detail')
                                <a href="{{ url('gameProfit/' . Request::segment(2) . '/' . Request::segment(3) . '?type=1&from=' . date('Y-m-d', strtotime('-6 month')) . '&to=' . date('Y-m-d')) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == 1 ? 'active' : '' }}">Last 6
                                    Months</a>
                                <a href="{{ url('gameProfit/' . Request::segment(2) . '/' . Request::segment(3) . '?type=2&from=' . date('Y-m-01') . '&to=' . date('Y-m-t')) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '2' ? 'active' : '' }}">Current
                                    Month</a>
                                <a href="{{ url('gameProfit/' . Request::segment(2) . '/' . Request::segment(3) . '?type=3&from=' . date('Y-m-d', strtotime('first day of last month')) . '&to=' . date('Y-m-d', strtotime('last day of last month'))) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '3' ? 'active' : '' }}">Last Month</a>
                                <a href="{{ url('gameProfit/' . Request::segment(2) . '/' . Request::segment(3) . '?type=4&from=' . $week_sd . '&to=' . $week_ed) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '4' ? 'active' : '' }}">Last Week</a>
                                <a href="{{ url('gameProfit/' . Request::segment(2) . '/' . Request::segment(3) . '?type=5&from=' . $checkmonday . '&to=' . date('Y-m-d', strtotime('+' . (7 - date('w')) . ' days'))) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '5' ? 'active' : '' }}">Current
                                    Week</a>
                                <a href="{{ url('gameProfit/' . Request::segment(2) . '/' . Request::segment(3) . '?type=6&from=' . date('Y-m-d', strtotime('-1 day')) . '&to=' . date('Y-m-d', strtotime('-1 day'))) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '6' ? 'active' : '' }}">Yesterday</a>
                                <a href="{{ url('gameProfit/' . Request::segment(2) . '/' . Request::segment(3) . '?type=7&from=' . date('Y-m-d') . '&to=' . date('Y-m-d')) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '7' ? 'active' : '' }}">Today</a>
                                <button type="button"
                                    class="btn btn-outline-info {{ $_GET['type'] == '8' ? 'active' : '' }}"
                                    data-toggle="modal" data-target="#myModal">Date Range</button>
                            @else
                                <a href="{{ url('gameProfit?type=1&from=' . date('Y-m-d', strtotime('-6 month')) . '&to=' . date('Y-m-d')) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == 1 ? 'active' : '' }}">Last 6
                                    Months</a>
                                <a href="{{ url('gameProfit?type=2&from=' . date('Y-m-01') . '&to=' . date('Y-m-t')) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '2' ? 'active' : '' }}">Current
                                    Month</a>
                                <a href="{{ url('gameProfit?type=3&from=' . date('Y-m-d', strtotime('first day of last month')) . '&to=' . date('Y-m-d', strtotime('last day of last month'))) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '3' ? 'active' : '' }}">Last
                                    Month</a>
                                <a href="{{ url('gameProfit?type=4&from=' . $week_sd . '&to=' . $week_ed) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '4' ? 'active' : '' }}">Last Week</a>
                                <a href="{{ url('gameProfit?type=5&from=' . $checkmonday . '&to=' . date('Y-m-d', strtotime('+' . (7 - date('w')) . ' days'))) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '5' ? 'active' : '' }}">Current
                                    Week</a>
                                <a href="{{ url('gameProfit?type=6&from=' . date('Y-m-d', strtotime('-1 day')) . '&to=' . date('Y-m-d', strtotime('-1 day'))) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '6' ? 'active' : '' }}">Yesterday</a>
                                <a href="{{ url('gameProfit?type=7&from=' . date('Y-m-d') . '&to=' . date('Y-m-d')) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '7' ? 'active' : '' }}">Today</a>
                                <button type="button"
                                    class="btn btn-outline-info {{ $_GET['type'] == '8' ? 'active' : '' }}"
                                    data-toggle="modal" data-target="#myModal">Date Range</button>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4 mt-2 d-flex justify-content-end">
                        <?php $type = $_GET['type']; ?>
                        <?php if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) { ?>
                        <span
                            class="bg-success text-white p-1 px-2 rounded"><?php echo date('d-m-Y', strtotime($_GET['from'])); ?></span>&nbsp;&nbsp;to&nbsp;&nbsp;<span
                            class="bg-success text-white p-1 px-2 rounded"><?php echo date('d-m-Y', strtotime($_GET['to'])); ?></span>
                        <?php } elseif ($type == 7 || $type == 6) { ?>
                        <span class="bg-success text-white p-1 px-2 rounded"><?php echo date('d-m-Y', strtotime($_GET['to'])); ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="breadcrumb bg-light" id="live2">
                        {{-- <div class="col-sm-4 text-center">
                            Total Profit RouletteTimer40<br>
                            <span>{{ moneyFormatIndia($total['rouletteTimer40']) ? moneyFormatIndia($total['rouletteTimer40']) : moneyFormatIndia(0) }}</span>
                        </div>
                        <div class="col-sm-4 text-center">
                            Total Profit RouletteTimer60<br>
                            <span>{{ moneyFormatIndia($total['rouletteTimer60']) ? moneyFormatIndia($total['rouletteTimer60']) : moneyFormatIndia(0) }}</span>
                        </div>
                        <div class="col-sm-4 text-center">
                            Total Profit Roulette<br>
                            <span>{{ moneyFormatIndia($total['roulette']) ? moneyFormatIndia($total['roulette']) : moneyFormatIndia(0) }}</span>
                        </div> --}}
                        <div class="col-sm-4 text-center">
                            Total Profit StockSkill<br>
                            <span>{{ moneyFormatIndia($total['stockskill']) ? moneyFormatIndia($total['stockskill']) : moneyFormatIndia(0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Game Summary</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                @if (Request::segment(2) == 'detail')
                    <form action="{{ url('gameProfit') }}" method="get">
                    @else
                        <form action="{{ url('gameProfit') }}" method="get">
                @endif
                <div class="modal-body">
                    <input type="hidden" name="type" value="8" />
                    From
                    <div class="input-group date datepicker" id="datePickerExample1">
                        <input type="text" class="form-control" name="from" placeholder="From"><span
                            class="input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                    To
                    <div class="input-group date datepicker" id="datePickerExample">
                        <input type="text" class="form-control" name="to" placeholder="To"><span
                            class="input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Get Summary</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/promise-polyfill/polyfill.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script type="text/javascript">
        $("#datePickerExample").datepicker();
        $("#datePickerExample1").datepicker();
        $(document).ready(function() {
            startTime();

            function startTime() {
                var today = new Date();
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();
                m = checkTime(m);
                s = checkTime(s);
                $('#clock').html(h + ":" + m + ":" + s);
                setTimeout(startTime, 1000);
            }

            function checkTime(i) {
                if (i < 10) {
                    i = "0" + i
                }; // add zero in front of numbers < 10
                return i;
            }
        });
    </script>
@endpush
