@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

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
                                    <th>Sl no.</th>
                                    <th>Username</th>
                                    <th>Opening Balance</th>
                                    <th>Points</th>
                                    <th>new Balance</th>
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
                                        @endphp</td>
                                        <td>{{ $pay['op_balance'] ?? '--' }}</td>
                                        <td>{{ $pay['point'] }}</td>
                                        <td>{{ $pay['op_balance'] ? $pay['op_balance'] + $pay['point'] : '--' }}</td>
                                        </td>
                                        <td>{{ $pay['comment'] ? $pay['comment'] : '--' }}</td>
                                        <td>{{ $pay['createDate'] ? date('d-m-Y h:i:s A', strtotime($pay['createDate'])) : date('d-m-Y h:i:s A', strtotime($pay['updatedAt'])) }}
                                        </td>
                                        <td>{{ $pay['allocated_point'] ? $pay['allocated_point'] : '--' }}</td>
                                        <td>{{ $pay['allocated_user'] ? $pay['allocated_user'] : '--' }}</td>
                                        <td>
                                            <span>Approved</span>
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
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript">
        // $('.delete-all').on('click', function(event) {
        //     event.preventDefault();
        //     const url = $(this).attr('href');
        //     const points = $(this).attr('id');
        //     const swalWithBootstrapButtons = Swal.mixin({
        //         input: 'text',
        //         confirmButtonText: 'Done',
        //         showCancelButton: true,
        //         progressSteps: ['1', '2']
        //     }).queue([{
        //         title: 'Your Points Transfer <br> Request Points:' + points,
        //         text: 'Enter points'
        //     }, {
        //         title: 'Are You Sure Transfer this Points',
        //         text: 'Admin Password'
        //     }, ]).then((result) => {
        //         if (result.value) {
        //             console.log('result.value', result.value);
        //             // window.location.href = url + "/" + result.value;
        //         }
        //     })
        // });
        $('.delete-all').on('click', function(event) {
            event.preventDefault();
            const url = $(this).attr('href');
            const points = $(this).attr('id');
            const {
                value: ipAddress
            } = Swal.fire({
                title: 'Enter your Points',
                input: 'text',
                text: 'Your Points Transfer Request Points : ' + points + "( " + convert(points) + " )",
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to write something!'
                    } else {
                        Swal.fire({
                            title: 'Admin Password',
                            input: 'text',
                            text: 'Are You Sure Transfer this Points : ' + value + "( " +
                                convert(value) + " )",
                            showCancelButton: true,
                            inputValidator: (password) => {
                                if (!password) {
                                    return 'You need to write something!'
                                } else {
                                    window.location.href = url + "/" + value + ',' +
                                        password;
                                }
                            }
                        })
                    }
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
