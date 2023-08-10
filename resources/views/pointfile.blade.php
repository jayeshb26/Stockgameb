@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

<?php
$mon = strtotime('last monday');
$monday = date('W', $mon) == date('W') ? $mon - 7 * 86400 : $mon;
$sunday = strtotime(date('Y-m-d', $monday) . ' +6 days');
$week_sd = date('Y-m-d', $monday);
$week_ed = date('Y-m-d', $sunday);
$checkmonday = date('l') == 'Monday' ? date('Y-m-d') : date('Y-m-d', strtotime('last monday'));
?>

@section('content')
    @if (Session::has('msg'))
        <div class="alert alert-danger" role="alert">
            {{ Session::has('msg') ? Session::get('msg') : '' }}
        </div>
    @elseif(Session::has('success'))
        <div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form method="get" action="{{ url('/PointFile') }}">
                        <div class="form-group d-flex">
                            <div class="col-sm-6">
                                <label class="control-label mt-2">From :</label>
                                <div class="input-group date datepicker" id="datePickerExample2">
                                    <input type="text" class="form-control" name="from" id="START_DATE_TIME"
                                        value={{ !empty($_GET['from']) ? date('m/d/Y', strtotime($_GET['from'])) : '' }}><span
                                        class="input-group-addon"><i data-feather="calendar"></i></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label mt-2">To :</label>
                                <div class="input-group date datepicker" id="datePickerExample1">
                                    <input type="text" class="form-control" name="to" id="END_DATE_TIME"
                                        value={{ !empty($_GET['to']) ? date('m/d/Y', strtotime($_GET['to'])) : '' }}><span
                                        class="input-group-addon"><i data-feather="calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <div class="col-sm-6">
                                <label class="control-label mt-2">Username :</label>
                                <select name="username" class="form-control">
                                    <option value="">Select the Username</option>
                                    @foreach ($data as $value)
                                        <option value="{{ $value['_id'] }}"
                                            {{ isset($_GET['username']) && $_GET['username'] == $value['_id'] ? 'selected' : '' }}>
                                            {{ $value['userName'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label mt-2">Date Range :</label>
                                <select name="searchlimit" id="searchlimit" class="form-control"
                                    onchange="javascript:showdaterange(this.value);">
                                    <option value="" selected="selected">Select</option>
                                    <option value="1"
                                        {{ isset($_GET['searchlimit']) && $_GET['searchlimit'] == 1 ? 'selected' : '' }}>
                                        Today</option>
                                    <option value="2"
                                        {{ isset($_GET['searchlimit']) && $_GET['searchlimit'] == 2 ? 'selected' : '' }}>
                                        Yesterday</option>
                                    <option value="3"
                                        {{ isset($_GET['searchlimit']) && $_GET['searchlimit'] == 3 ? 'selected' : '' }}>
                                        This Week</option>
                                    <option value="4"
                                        {{ isset($_GET['searchlimit']) && $_GET['searchlimit'] == 4 ? 'selected' : '' }}>
                                        Last Week</option>
                                    <option value="5"
                                        {{ isset($_GET['searchlimit']) && $_GET['searchlimit'] == 5 ? 'selected' : '' }}>
                                        This Month</option>
                                    <option value="6"
                                        {{ isset($_GET['searchlimit']) && $_GET['searchlimit'] == 6 ? 'selected' : '' }}>
                                        Last Month</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <div class="col-sm-12">
                                <input class="btn btn-primary" type="submit" value="Submit">
                            </div>
                        </div>
                    </form>
                    <div class="row text-right">
                        <a href="javascript:history.back()" class="btn btn-success"><i
                                class="fa fa-arrow-left mr-2"></i>Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="">
                        <div class="col-md-12 d-flex justify-content-between">
                            <div class="col-md-6">
                                <h6 class="card-title">Point Requests</h6>
                            </div>
                            @if (Session::get('role') != 'Admin')
                                <div class="col-md-6 row text-right">
                                    <a href="{{ url('/point_requests_create') }}" class="btn btn-success mr-3"><i
                                            class="fa fa-plus"></i>
                                        Points Request</a>
                                    <a href="javascript:history.back()" class="btn btn-success"><i
                                            class="fa fa-arrow-left mr-2"></i>Back</a>
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Date</th>
                                    <th>Receiver</th>
                                    <th>Opening Balance</th>
                                    <th>In </th>
                                    <th>Out</th>
                                    <th>New Balance</th>
                                    <th>Sender </th>
                                    <th>Trans.Id </th>
                                    <th>Comments </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                    $d = 0;
                                    $c = 0;
                                @endphp
                                @foreach ($payment as $pay)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $pay['date'] }}</td>
                                        <td>{{ $pay['user'] }}</td>
                                        <td>{{ $pay['op_balance'] ?? '--' }}</td>
                                        @if ($pay['type'] == 'adjustment')
                                            <td>
                                                --
                                            </td>
                                            <td>
                                                @if ($pay['status'] != 'pending')
                                                    @php $c += $pay['creditPoint']; @endphp
                                                @endif
                                                {{ number_format($pay['creditPoint'], 2) }}
                                            </td>
                                        @else
                                            <td>
                                                @if ($pay['status'] != 'pending')
                                                    @php $d += $pay['creditPoint']; @endphp
                                                @endif
                                                {{ number_format($pay['creditPoint'], 2) }}
                                            </td>
                                            <td>
                                                --
                                            </td>
                                        @endif
                                        <td>{{ $pay['op_balance'] ? ($pay['type'] == 'adjustment' ? $pay['op_balance'] - $pay['creditPoint'] : $pay['op_balance'] + $pay['creditPoint']) : '--' }}
                                        </td>
                                        </td>
                                        <td>{{ $pay['userName'] }}</td>
                                        <td>{{ $pay['_id'] }}</td>
                                        <td>{{ $pay['comment'] ?? '--' }}</td>
                                    </tr>
                                @endforeach
                                <tr class="highlight">
                                    <td>Total</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ number_format($d, 2) }}</td>
                                    <td>{{ number_format($c, 2) }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript">
        $('.delete-all').on('click', function(event) {
            event.preventDefault();
            const url = $(this).attr('href');
            const swalWithBootstrapButtons = Swal.mixin({
                input: 'text',
                confirmButtonText: 'Done',
                showCancelButton: true,
                progressSteps: ['1', '2']
            }).queue([{
                title: 'Your Points Transfer',
                text: 'Enter points'
            }, {
                title: 'Are You Sure Transfer this Points',
                text: 'Admin Password'
            }, ]).then((result) => {
                if (result.value) {
                    window.location.href = url + "/" + result.value;
                }
            })
        });
    </script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script type="text/javascript">
        $("#datePickerExample2").datepicker();
        $("#datePickerExample1").datepicker();

        function showdaterange(vid) {
            if (vid != '') {
                var sdate = '';
                var edate = '';

                if (vid == "1") {
                    sdate = <?php echo "'" . date('Y-m-d') . "'"; ?>;
                    edate = <?php echo "'" . date('Y-m-d') . "'"; ?>;
                }
                if (vid == "2") {
                    sdate = <?php echo "'" . date('Y-m-d', strtotime('-1 day')) . "'"; ?>;
                    edate = <?php echo "'" . date('Y-m-d', strtotime('-1 day')) . "'"; ?>;
                }
                if (vid == "3") {
                    sdate = <?php echo "'" . $checkmonday . "'"; ?>;
                    edate = <?php echo "'" . date('Y-m-d', strtotime('+' . (7 - date('w')) . ' days')) . "'"; ?>;
                }
                if (vid == "4") {
                    sdate = <?php echo "'" . $week_sd . "'"; ?>;
                    edate = <?php echo "'" . $week_ed . "'"; ?>;
                }
                if (vid == "5") {
                    sdate = <?php echo "'" . date('Y-m-01') . "'"; ?>;
                    edate = <?php echo "'" . date('Y-m-d') . "'"; ?>;
                }
                if (vid == "6") {
                    sdate = <?php echo "'" . date('Y-m-d', strtotime('first day of last month')) . "'"; ?>;
                    edate = <?php echo "'" . date('Y-m-d', strtotime('last day of last month')) . "'"; ?>;
                }
                document.getElementById("START_DATE_TIME").value = sdate;
                document.getElementById("END_DATE_TIME").value = edate;
            }
        }
    </script>

    <script type="text/javascript">
        function filterText() {
            var rex = new RegExp($('#filterText').val());
            if (rex == "/all/") {
                clearFilter()
            } else {
                $('.content').hide();
                $('.content').filter(function() {
                    return rex.test($(this).text());
                }).show();
            }
        }

        function clearFilter() {
            $('.filterText').val('');
            $('.content').show();
        }
    </script>
@endpush
