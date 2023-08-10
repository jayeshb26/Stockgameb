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
                    <form method="get" id="myFormID" action="{{ url('/winhistory') }}">
                        <div class="forms-sample">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <div class="input-group date datepicker" id="datePickerExample1">
                                        <input type="text" class="form-control" name="from" placeholder="From"><span
                                            class="input-group-addon"><i data-feather="calendar"></i></span>
                                    </div>
                                </div>
                                <div class="col-sm-1 text-center mt-2">To</div>
                                <div class="col-sm-4">
                                    <div class="input-group date datepicker" id="datePickerExample">
                                        <input type="text" class="form-control" name="to" placeholder="To"><span
                                            class="input-group-addon"><i data-feather="calendar"></i></span>
                                    </div>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <select class="form-control" name="series">
                                        <option selected disabled>Select Series No</option>
                                        <option value="1">10</option>
                                        <option value="3">30</option>
                                        <option value="5">50</option>
                                        <option value="6">60</option>
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <input class="btn btn-primary form-control" type="submit" value="Submit">
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
                    <h6 class="card-title">Win History</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SL.No</th>
                                    <th>Result</th>
                                    <th>X</th>
                                    <th>Draw Date</th>
                                    <th>Draw Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $SR_No = 1;
                                @endphp
                                @foreach ($win as $key => $value)
                                    @if (Session::get('role') == 'Admin')
                                        <tr role="row" class="odd">
                                            <td class=""><?= $win->firstItem() + $key ?></td>
                                            <td>{{ $value['result'] }}</td>
                                            <td>{{ $value['x'] == '1' ? 'N' : $value['x'] . 'x' }}</td>
                                            <td>{{ date('d-m-Y', strtotime($value['DrDate'])) }}</td>
                                            <td>{{ date('h:i:s A', strtotime(str_replace(' ', '', $value['DrTime']))) }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        @php
                            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                                $url = 'https://';
                            } else {
                                $url = 'http://';
                            }
                            $url .= $_SERVER['HTTP_HOST'];
                            $url .= $_SERVER['REQUEST_URI'];
                        @endphp
                        <div class="
                                                mt-3">{{ $win->links() }}
                    </div>
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
