@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <style type="text/css" rel="stylesheet">
        .well {
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 5%);
            box-shadow: inset 0 1px 1px rgb(0 0 0 / 5%);
        }

    </style>
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
            <div class="card" style="border: 2px solid #0b7c3c; border-radius:2px">
                <div class="card-header d-flex justify-content-between">
                    <h6>Balance Transfer</h6>

                    <div class="row text-right">
                        <a href="javascript:history.back()" class="btn btn-success"><i
                                class="fa fa-arrow-left mr-2"></i>Back</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (Session::has('msg'))
                        <div class="alert alert-danger" role="alert">
                            {{ Session::has('msg') ? Session::get('msg') : '' }}
                        </div>
                    @elseif(Session::has('success'))
                        <div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
                    @endif

                    <form method="post" action="{{ url('users/transferCredits/') }}">
                        @csrf
                        <div class="form-group">
                            <label>Username :</label>
                            <select class="js-example-basic-single w-100" name="amount" id="select">
                                <option value="-1">Enter the Username</option>
                                @foreach ($data as $value)
                                    <option value="{{ $value['_id'] }}">{{ $value['userName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    <div id="section" class="well breadcrumb bg-light">
                        <table>
                            <tr>
                                <td>Name</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Username</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card" style="border: 2px solid #0b7c3c; border-radius:2px">
                <div class="card-body">
                    <h6 class="card-title">Pending Accepts</h6>
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SL.No</th>
                                    <th>UserName</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="pending">
                                @php $no = 1; @endphp
                                @foreach ($pending_accept as $pa)
                                    <tr>
                                        <th>{{ $no++ }}</th>
                                        <th>
                                            {{ $pa['user'] }}
                                        </th>
                                        <th>{{ number_format($pa['creditPoint'], 2) }}</th>
                                        <th>
                                            <a href="{{ url('success/' . $pa['_id']) }}" class="btn btn-outline-success"
                                                title="Accept">Accept</a>
                                            <a href="{{ url('reject/' . $pa['_id']) }}"
                                                class="btn btn-outline-danger delete-confirm" title="Reject">Reject</a>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card" >
            <div class="card" style="border: 2px solid #0b7c3c; border-radius:2px">
                <div class="card-body">
                    <h6 class="card-title">Transfers Status</h6>
                    <div class="table-responsive">
                        <table id="dataTableExample1" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SL.No</th>
                                    <th>UserName</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="success">
                                @php $no = 1; @endphp
                                @foreach ($pending_transfer as $pa)
                                    <tr>
                                        <th>{{ $no++ }}</th>
                                        <th>
                                            @foreach ($data as $us)
                                                @if ($us['_id'] == $pa['toId'])
                                                    {{ $us['userName'] }}
                                                @endif
                                            @endforeach
                                        </th>
                                        <th>{{ number_format($pa['creditPoint'], 2) }}</th>
                                        <th>
                                            @if ($pa['status'] == 'success')
                                                <span title="Ban User">Transfer <span
                                                        class="text-success">Success</span></span>
                                            @elseif($pa['status'] == 'pending')
                                                <span title="Ban User">Transfer <span
                                                        class="text-primary">Pending</span></span>
                                            @elseif($pa['status'] == 'rejected')
                                                <span title="Ban User">Transfer <span
                                                        class="text-danger">Reject</span></span>
                                            @endif
                                        </th>
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
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush

@push('custom-scripts')
    <script type="text/javascript">
        $('#dataTableExample1').DataTable({
            "aLengthMenu": [
                [10, 30, 50, -1],
                [10, 30, 50, "All"]
            ],
            "iDisplayLength": 10,
            "language": {
                search: ""
            }
        });
        $('#dataTableExample1').each(function() {
            var datatable = $(this);
            // SEARCH - Add the placeholder for Search and Turn this into in-line form control
            var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
            search_input.attr('placeholder', 'Search');
            search_input.removeClass('form-control-sm');
            // LENGTH - Inline-Form control
            var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
            length_sel.removeClass('form-control-sm');
        });

        $('#section').hide();
        $(document).ready(function() {
            $('#select').change(function() {
                var Role = $(this).val();
                uri = "{{ url('/search') }}";
                var token = $('input[name="_token"]').val();
                $.ajax({
                    url: uri,
                    type: 'POST',
                    data: {
                        id: Role,
                        _token: token
                    },
                    success: function(res) {
                        $('#section').show();
                        $('#section').html(res);
                    }
                });
            });
        });
    </script>
    <script type="text/javascript">
        $('.delete-confirm').on('click', function(event) {
            event.preventDefault();
            const url = $(this).attr('href');
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
            })
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You Reject The Credit Point",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'ml-2',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    window.location.href = url;
                    swalWithBootstrapButtons.fire(
                        'Deleted!',
                        'Your Credit Point is Rejected.',
                        'success'
                    )
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your Credit Point is safe :)',
                        'error'
                    )
                }
            })
        });
    </script>
@endpush
