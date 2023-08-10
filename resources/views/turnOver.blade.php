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
                <div class="card-body">
                    <div class="col-md-8 col-sm-12 col-xs-12 mt-2">
                        <div class="btn-group flex-wrap">
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
                                <a href="{{ url('Tnover/' . Request::segment(2) . '/' . Request::segment(3) . '?role=' . $_GET['role'] . '&type=1&from=' . date('Y-m-d', strtotime('-6 month')) . '&to=' . date('Y-m-d')) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == 1 ? 'active' : '' }}">Last 6
                                    Months</a>
                                <a href="{{ url('Tnover/' . Request::segment(2) . '/' . Request::segment(3) . '?role=' . $_GET['role'] . '&type=2&from=' . date('Y-m-01') . '&to=' . date('Y-m-t')) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '2' ? 'active' : '' }}">Current
                                    Month</a>
                                <a href="{{ url('Tnover/' . Request::segment(2) . '/' . Request::segment(3) . '?role=' . $_GET['role'] . '&type=3&from=' . date('Y-m-d', strtotime('first day of last month')) . '&to=' . date('Y-m-d', strtotime('last day of last month'))) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '3' ? 'active' : '' }}">Last
                                    Month</a>
                                <a href="{{ url('Tnover/' . Request::segment(2) . '/' . Request::segment(3) . '?role=' . $_GET['role'] . '&type=4&from=' . $week_sd . '&to=' . $week_ed) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '4' ? 'active' : '' }}">Last
                                    Week</a>
                                <a href="{{ url('Tnover/' . Request::segment(2) . '/' . Request::segment(3) . '?role=' . $_GET['role'] . '&type=5&from=' . $checkmonday . '&to=' . date('Y-m-d', strtotime('+' . (7 - date('w')) . ' days'))) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '5' ? 'active' : '' }}">Current
                                    Week</a>
                                <a href="{{ url('Tnover/' . Request::segment(2) . '/' . Request::segment(3) . '?role=' . $_GET['role'] . '&type=6&from=' . date('Y-m-d', strtotime('-1 day')) . '&to=' . date('Y-m-d', strtotime('-1 day'))) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '6' ? 'active' : '' }}">Yesterday</a>
                                <a href="{{ url('Tnover/' . Request::segment(2) . '/' . Request::segment(3) . '?role=' . $_GET['role'] . '&type=7&from=' . date('Y-m-d') . '&to=' . date('Y-m-d')) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '7' ? 'active' : '' }}">Today</a>
                                <button type="button"
                                    class="btn btn-outline-info {{ $_GET['type'] == '8' ? 'active' : '' }}"
                                    data-toggle="modal" data-target="#myModal">Date Range</button>
                            @else
                                <a href="{{ url('Tnover?role=' . $_GET['role'] . '&type=1&from=' . date('Y-m-d', strtotime('-6 month')) . '&to=' . date('Y-m-d')) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == 1 ? 'active' : '' }}">Last 6
                                    Months</a>
                                <a href="{{ url('Tnover?role=' . $_GET['role'] . '&type=2&from=' . date('Y-m-01') . '&to=' . date('Y-m-t')) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '2' ? 'active' : '' }}">Current
                                    Month</a>
                                <a href="{{ url('Tnover?role=' . $_GET['role'] . '&type=3&from=' . date('Y-m-d', strtotime('first day of last month')) . '&to=' . date('Y-m-d', strtotime('last day of last month'))) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '3' ? 'active' : '' }}">Last
                                    Month</a>
                                <a href="{{ url('Tnover?role=' . $_GET['role'] . '&type=4&from=' . $week_sd . '&to=' . $week_ed) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '4' ? 'active' : '' }}">Last
                                    Week</a>
                                <a href="{{ url('Tnover?role=' . $_GET['role'] . '&type=5&from=' . $checkmonday . '&to=' . date('Y-m-d', strtotime('+' . (7 - date('w')) . ' days'))) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '5' ? 'active' : '' }}">Current
                                    Week</a>
                                <a href="{{ url('Tnover?role=' . $_GET['role'] . '&type=6&from=' . date('Y-m-d', strtotime('-1 day')) . '&to=' . date('Y-m-d', strtotime('-1 day'))) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '6' ? 'active' : '' }}">Yesterday</a>
                                <a href="{{ url('Tnover?role=' . $_GET['role'] . '&type=7&from=' . date('Y-m-d') . '&to=' . date('Y-m-d')) }}"
                                    class="btn btn-outline-info {{ $_GET['type'] == '7' ? 'active' : '' }}">Today</a>
                                <button type="button"
                                    class="btn btn-outline-info {{ $_GET['type'] == '8' ? 'active' : '' }}"
                                    data-toggle="modal" data-target="#myModal">Date Range</button>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 mt-2">
                        <?php $type = $_GET['type']; ?>
                        <?php if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) { ?>
                        <span
                            class="bg-success text-white p-1 px-2 rounded"><?php echo date('d-m-Y', strtotime($_GET['from'])); ?></span>&nbsp;&nbsp;to&nbsp;&nbsp;<span
                            class="bg-success text-white p-1 px-2 rounded"><?php echo date('d-m-Y', strtotime($_GET['to'])); ?></span>
                        <?php } elseif ($type == 7 || $type == 6) { ?>
                        <span class="bg-success text-white p-1 px-2 rounded"><?php echo date('d-m-Y', strtotime($_GET['to'])); ?></span>
                        <?php } ?>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 mt-2">
                        <a href="javascript:history.back()" class="btn btn-success"><i
                                class="fa fa-arrow-left mr-2"></i>Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="breadcrumb bg-light" id="live2">
                        <div class="col-sm-4 text-center">
                            Total Play Points<br> <span>{{ moneyFormatIndia($total['totalPlayPoints']) }}</span>
                        </div>
                        <div class="col-sm-4 text-center">
                            Total Win Points <br> <span>{{ moneyFormatIndia($total['TotalWinPoints']) }}</span>
                        </div>
                        <div class="col-sm-4 text-center">
                            Total End Point <br> <span>{{ moneyFormatIndia($total['EndPoint']) }}</span>
                        </div>
                        <div class="col-sm-4 text-center">
                            Total Margin Point <br> <span>{{ moneyFormatIndia($total['Margin']) }}</span>
                        </div>
                        <div class="col-sm-4 text-center">
                            Total Net Point <br> <span>{{ moneyFormatIndia($total['NetProfit']) }}</span>
                        </div>
                        <div class="col-sm-4 text-center">
                            Total SuperDistributedProfit <br>
                            <span>{{ moneyFormatIndia($total['SuperDistributedProfit']) }}</span>
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
                    <form action="{{ url('Tnover') }}" method="get">
                    @else
                        <form action="{{ url('Tnover') }}" method="get">
                @endif
                <div class="modal-body">
                    <input type="hidden" name="type" value="8" />
                    <input type="hidden" name="role" value="{{ $_GET['role'] }}" />
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
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">TurnOver Player</h6>
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Play Points</th>
                                    <th>Win Points</th>
                                    <th>End Point</th>
                                    @if (Session::get('role') != 'player')
                                        <th>Margin</th>
                                        <th>Net point</th>
                                        <th>Your Margin</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                    $playP = 0;
                                    $win = 0;
                                    $end = 0;
                                    $marginp = 0;
                                    $netp = 0;
                                    $yourMargin = 0;
                                    setlocale(LC_MONETARY, 'en_IN');
                                @endphp
                                @if (isset($data) && !empty($data))
                                    @foreach ($data as $play)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            @if ($play['role'] == 'Admin')
                                                @php
                                                    $playP += $total['totalPlayPoints'];
                                                    $win += $total['TotalWinPoints'];
                                                    $end += $total['EndPoint'];
                                                    $marginp += 0;
                                                    $netp += 0;
                                                    $yourMargin += 0;
                                                @endphp
                                                <td>
                                                    <a
                                                        href="{{ url('Tnover/detail/' . $play['_id'] . '?role=' . $_GET['role'] . '&type=' . $_GET['type'] . '&from=' . $_GET['from'] . '&to=' . $_GET['to']) }}">
                                                        {{ $play['userName'] }}
                                                        <span class="badge badge-secondary rounded-pill"><i
                                                                class="fa fa-eye"></i></span></a>
                                                </td>
                                                <td>{{ $play['name'] }}</td>
                                                <td>{{ moneyFormatIndia($total['totalPlayPoints']) }}</td>
                                                <td>{{ moneyFormatIndia($total['TotalWinPoints']) }}</td>
                                                <td>{{ moneyFormatIndia($total['EndPoint']) }}</td>
                                                <td>{{ moneyFormatIndia(0) }}</td>
                                                <td>{{ moneyFormatIndia(0) }}</td>
                                                <td>{{ moneyFormatIndia(0) }}</td>
                                            @elseif($play['role'] == 'agent' || $play['role'] == 'premium' || $play['role'] == 'executive' || $play['role'] == 'classic' || $play['role'] == 'player')
                                                @if ($play['role'] == 'player')
                                                    <td><a href="{{ url('player/detail/' . $play['_id']) }}">{{ $play['userName'] }}
                                                            <span class="badge badge-secondary rounded-pill"><i
                                                                    class="fa fa-eye"></i></span></a></td>
                                                @else
                                                    <td><a
                                                            href="{{ url('Tnover/detail/' . $play['_id'] . '?role=' . $_GET['role'] . '&type=' . $_GET['type'] . '&from=' . $_GET['from'] . '&to=' . $_GET['to']) }}">{{ $play['userName'] }}
                                                            <span class="badge badge-secondary rounded-pill"><i
                                                                    class="fa fa-eye"></i></span></a></td>
                                                @endif
                                                <td>{{ $play['name'] }}</td>
                                                <td>{{ moneyFormatIndia($play['playPoint']) }}</td>
                                                <td>{{ moneyFormatIndia($play['wonPoint']) }}</td>
                                                @if ($_GET['role'] == 'agent')
                                                    <?php $endPoint = $play['playPoint'] - $play['wonPoint'];
                                                    $margin = ($endPoint * $play['commission']) / 100;
                                                    $net = $endPoint - $margin;
                                                    ?>
                                                @else
                                                    <?php $endPoint = $play['playPoint'] - $play['wonPoint'];
                                                    $margin = ($play['playPoint'] * $play['commission']) / 100;
                                                    $net = $endPoint - $margin;
                                                    ?>
                                                @endif
                                                <td>{{ moneyFormatIndia($endPoint) }}</td>
                                                <td>{{ moneyFormatIndia($margin) }}</td>
                                                <td>{{ moneyFormatIndia($net) }}</td>
                                                <td>{{ moneyFormatIndia($play['SuperDistributedProfit']) }}</td>
                                                @php
                                                    $playP += $play['playPoint'];
                                                    $win += $play['wonPoint'];
                                                    $end += $endPoint;
                                                    $marginp += $margin;
                                                    $netp += $net;
                                                    $yourMargin += $play['SuperDistributedProfit'];
                                                @endphp
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td colspan="2">Total Amount :</td>
                                    <td>{{ moneyFormatIndia($playP) }}</td>
                                    <td>{{ moneyFormatIndia($win) }}</td>
                                    <td>{{ moneyFormatIndia($end) }}</td>
                                    <td>{{ moneyFormatIndia($marginp) }}</td>
                                    <td>{{ moneyFormatIndia($netp) }}</td>
                                    <td>{{ moneyFormatIndia($yourMargin) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
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
