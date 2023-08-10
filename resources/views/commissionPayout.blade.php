@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
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
                    <form id="myFormID" method="get" action="{{ url('/cmbreport') }}">
                        <div class="forms-sample">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <div class="input-group date datepicker" id="datePickerExample1">
                                        <input type="text" class="form-control" name="from" placeholder="From"
                                            value={{ !empty($_GET['from']) ? date('m/d/Y', strtotime($_GET['from'])) : '' }}><span
                                            class="input-group-addon"><i data-feather="calendar"></i></span>
                                    </div>
                                </div>
                                <div class="col-sm-1 text-center mt-2">To</div>
                                <div class="col-sm-4">
                                    <div class="input-group date datepicker" id="datePickerExample2">
                                        <input type="text" class="form-control" name="to" placeholder="To"
                                            value={{ !empty($_GET['to']) ? date('m/d/Y', strtotime($_GET['to'])) : '' }}><span
                                            class="input-group-addon"><i data-feather="calendar"></i></span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <input class="btn btn-primary form-control" type="submit" value="Search">
                                </div>
                                <div class="col-sm-1">
                                    <a href="javascript:history.back()" class="btn btn-success"><i
                                            class="fa fa-arrow-left mr-2"></i>Back</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sl.no</th>
                                    <th>Date</th>
                                    <th>Username</th>
                                    <th>User Type</th>
                                    <th>Name</th>
                                    @if (Session::get('role') == 'Admin')
                                        <th>Agent Commission</th>
                                    @endif
                                    @if (Session::get('role') == 'agent' || Session::get('role') == 'Admin')
                                        <th>Premium Commission</th>
                                    @endif
                                    @if (Session::get('role') == 'premium' || Session::get('role') == 'Admin' || Session::get('role') == 'agent')
                                        <th>Exexutive Commission</th>
                                    @endif
                                    @if (Session::get('role') == 'executive' || Session::get('role') == 'premium' || Session::get('role') == 'Admin' || Session::get('role') == 'agent')
                                        <th>Classic Commission</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                    $totalA = 0;
                                    $totalP = 0;
                                    $totalE = 0;
                                    $totalC = 0;
                                @endphp
                                @foreach ($data as $day => $value)
                                    @php
                                        if (Session::get('role') == 'Admin') {
                                            $totalA += $value['agentCommission'];
                                        }
                                        if (Session::get('role') == 'agent' || Session::get('role') == 'Admin') {
                                            $totalP += $value['premiumCommission'];
                                        }
                                        if (Session::get('role') == 'premium' || Session::get('role') == 'Admin' || Session::get('role') == 'agent') {
                                            $totalE += $value['ExecutiveCommission'];
                                        }
                                        if (Session::get('role') == 'executive' || Session::get('role') == 'premium' || Session::get('role') == 'Admin' || Session::get('role') == 'agent') {
                                            $totalC += $value['classicCommission'];
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $day }}</td>
                                        <td>{{ $value['userName'] }}</td>
                                        <td>{{ $value['role'] }}</td>
                                        <td>{{ $value['name'] }}</td>
                                        @if (Session::get('role') == 'Admin')
                                            <td>{{ $value['agentCommission'] }}</td>
                                        @endif
                                        @if (Session::get('role') == 'Admin' || Session::get('role') == 'agent')
                                            <td>{{ $value['premiumCommission'] }}</td>
                                        @endif
                                        @if (Session::get('role') == 'premium' || Session::get('role') == 'Admin' || Session::get('role') == 'agent')
                                            <td>{{ $value['ExecutiveCommission'] }}</td>
                                        @endif
                                        @if (Session::get('role') == 'executive' || Session::get('role') == 'premium' || Session::get('role') == 'Admin' || Session::get('role') == 'agent')
                                            <td>{{ $value['classicCommission'] }}</td>
                                        @endif
                                        {{-- @if (Session::get('role') == 'Admin')
                                            <td>{{ number_format($toS, 2) }}</td>
                                        @endif
                                        @if (Session::get('role') == 'superDistributer' || Session::get('role') == 'Admin')
                                            <td>{{ number_format($toD, 2) }}</td>
                                        @endif
                                        <td>{{ number_format($toR, 2) }}</td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td colspan="2">Total Commission</td>
                                    <td></td>
                                    <td></td>
                                    @if (Session::get('role') == 'Admin')
                                        <td>{{ number_format($totalA, 2) }}</td>
                                    @endif
                                    @if (Session::get('role') == 'Admin' || Session::get('role') == 'agent')
                                        <td>{{ number_format($totalP, 2) }}</td>
                                    @endif
                                    @if (Session::get('role') == 'premium' || Session::get('role') == 'Admin' || Session::get('role') == 'agent')
                                        <td>{{ number_format($totalE, 2) }}</td>
                                    @endif
                                    @if (Session::get('role') == 'executive' || Session::get('role') == 'premium' || Session::get('role') == 'Admin' || Session::get('role') == 'agent')
                                        <td>{{ number_format($totalC, 2) }}</td>
                                    @endif
                                    {{-- @if (Session::get('role') == 'Admin')
                                        <td></td>
                                        <td>{{ number_format($totalS, 2) }}</td>
                                    @endif
                                    @if (Session::get('role') == 'superDistributer' || Session::get('role') == 'Admin')
                                        <td>{{ number_format($totalD, 2) }}</td>
                                    @endif
                                    <td>{{ number_format($totalR, 2) }}</td> --}}
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
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
@endpush


@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
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
