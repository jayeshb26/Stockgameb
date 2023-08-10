@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form method="get" action="{{ url('/transactions') }}">
                        <div class="form-group d-flex">
                            <label class="col-sm-1 control-label mt-2">From :</label>
                            <div class="col-sm-5">
                                <div class="input-group date datepicker" id="datePickerExample2">
                                    <input type="text" class="form-control" name="from" id="from"
                                        value={{ !empty($_GET['from']) ? date('m/d/Y', strtotime($_GET['from'])) : '' }}><span
                                        class="input-group-addon"><i data-feather="calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-1 control-label mt-2">To :</label>
                            <div class="col-sm-5">
                                <div class="input-group date datepicker" id="datePickerExample1">
                                    <input type="text" class="form-control" name="to" id="to"
                                        value={{ !empty($_GET['to']) ? date('m/d/Y', strtotime($_GET['to'])) : '' }}><span
                                        class="input-group-addon"><i data-feather="calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-1 control-label mt-2">Username :</label>
                            <div class="col-sm-5">
                                <select name="username" class="form-control">
                                    <option value="">Select the Username</option>
                                    @foreach ($users as $value)
                                        <option value="{{ $value['_id'] }}"
                                            {{ isset($_GET['username']) && $_GET['username'] == $value['_id'] ? 'selected' : '' }}>
                                            {{ $value['userName'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <div class="col-sm-1 offset-sm-1">
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
                    <h6 class="card-title">Transactions</h6>
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sl no.</th>
                                    <th>Username</th>
                                    <th>Opening Balance</th>
                                    <th>Points</th>
                                    <th>New Balance</th>
                                    <th>Comment </th>
                                    <th>Date </th>
                                    <th>Allocated Points</th>
                                    <th>Allocated by</th>
                                    <th>Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                    $d = 0;
                                    $c = 0;
                                @endphp
                                @foreach ($data as $pay)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>@php
                                            $data = App\User::where('_id', new \MongoDB\BSON\ObjectID($pay['playerId']))->first();
                                            echo $data->userName;
                                        @endphp
                                        </td>
                                        <td>{{ $pay->op_balance ?? '--' }}</td>
                                        <td>{{ $pay->point }}</td>
                                        <td>{{ $pay->op_balance ? $pay->op_balance + $pay->point : '--' }}</td>
                                        <td>{{ $pay['comment'] ? $pay['comment'] : '--' }}</td>
                                        <td>{{ $pay['createDate'] ? date('d-m-Y h:i:s A', strtotime($pay['createDate'])) : date('d-m-Y h:i:s A', strtotime($pay['updatedAt'])) }}
                                        </td>
                                        <td>{{ $pay['allocated_point'] ? $pay['allocated_point'] : '--' }}</td>
                                        <td>{{ $pay['allocated_user'] ? $pay['allocated_user'] : '--' }}</td>
                                        <td>
                                            @if ($pay['status'] == 'Pending')
                                                <a href="{{ url('point_request/' . $pay['_id']) }}"
                                                    class="btn btn-outline-success delete-all"
                                                    id="{{ $pay['point'] }}">Approve
                                                    Data</a>
                                            @else
                                                <span>Approved</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script type="text/javascript">
        $("#datePickerExample2").datepicker();
        $("#datePickerExample1").datepicker();
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
