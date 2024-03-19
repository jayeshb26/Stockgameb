@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    {{-- <nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Tables</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Table</li>
  </ol>
</nav> --}}

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
                <div class="col-md-12 row">
                    <div class="col-md-4">
                        <h6 class="card-title">Stocks</h6>
                    </div>
                    <div class="col row flex-grow-0 ">
                        <a href="javascript:void(0);" class="btn btn-primary" id="active_stock_btn"
                            >Active</a>
                        </div>
                        <div class="col row flex-grow-0">
                            <a href="javascript:void(0);" class="btn btn-secondary" id="inactive_stock_btn"
                            >Inactive</a>
                        </div>
                    <label for="Market" class="col row" style="text-align: center;flex-grow: 0.9;">Market Type </label>
                    <select id='filterText' name="Market" class='col-2 mr-1 row' onchange='filterText()'
                       style="left: -50px; color:black;"
                       >
                        <option value="">Select Market Field</option>
                        @foreach ($markets as $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>

                    <div class="col-md-2 row ">
                        <a href="{{ url('/stocks/create') }}" class="btn btn-success"><i class="fa fa-plus"></i>
                            Add Stock
                        </a>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">



                    {{--  <p class="card-description">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p>  --}}
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="">
                                        <input type="checkbox" id="stock_checkbox">
                                        All
                                    </th>
                                    <th>SL.No</th>
                                    <th>Name</th>
                                    <th>Symbol</th>
                                    <th>Status</th>
                                    <th>Market</th>
                                    <th>Created_Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $SR_No = 1;
                                @endphp
                                @foreach ($data as $key => $value)
                                    <tr role="row" class="odd content">
                                        <td class="">
                                            <input type="checkbox" class="row_stock_checkbox"
                                                value="{{ $value['number'] }}">
                                        </td>
                                        <td class="">
                                            {{ $value['number']+ 1 }}
                                        </td>
                                        <td>{{ $value['name'] }}</td>
                                        <td>{{ $value['symbol'] }}</td>
                                        <td>{{ $value['status'] == 1 ? 'Active' : 'Inactive' }}</td>
                                        <td>{{ $value['market'] }}</td>
                                        <td>{{ date('d-m-Y h:i:s A', strtotime($value['created_at'])) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ url('stocks/' . $value['_id'] . '/edit') }}" type="button"
                                                    class="btn btn-outline-info" title="Edit Stock"><i
                                                        class="mdi mdi-pencil-box" style="font-size:20px;"></i></a>
                                                @if (Session::get('role') == 'Admin')
                                                    {{-- <a href="{{ url('stocks/delete/' . $value['_id']) }}" class="btn btn-outline-danger delete-confirm" title="Delete">
                                                        <i class="mdi mdi-delete" style="font-size:20px;"></i>
                                                    </a> --}}
                                                @endif
                                            </div>
                                            {{-- href="{{ url('users/delete/'.$value['_id'])}}" --}}
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
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/promise-polyfill/polyfill.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
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

    <script type="text/javascript">
        var myDataTable = $('#datatable').DataTable({
            // Other DataTable configurations
        });

        // Create a function for filtering
        function filterText() {
            var symbol = $('#filterText').val().toUpperCase(); // Convert input to uppercase for case-insensitive matching

            // Clear previous search results
            myDataTable.search('').draw();

            if (symbol) {
                // Apply the search filter
                myDataTable.search(symbol).draw();
            }
        }

        // Add an event listener for the input
        $('#filterText').on('input', filterText);
    </script>

    {{-- <script src="{{ asset('assets/js/sweet-alert.js') }}"></script> --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'ml-2',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    window.location.href = url;
                    swalWithBootstrapButtons.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    )
                }
            })
        });

        $('input:checkbox.row_stock_checkbox').click(function() {
            let count = $("input[class='row_stock_checkbox']:checked").length;
            if (count > 0) {
                $('#inactive_stock_btn').show();
                $('#active_stock_btn').show();
            } else {
                $('#inactive_stock_btn').hide();
                $('#active_stock_btn').hide();
            }

            let unchecked = $("input[class='row_stock_checkbox']").not(':checked').length;
            if (unchecked < 1) {
                $('#stock_checkbox').prop('checked', true);
            } else {
                $('#stock_checkbox').prop('checked', false);
            }
        });

        $("input:checkbox#stock_checkbox").click(function() {
            let count = $("input[class='row_stock_checkbox']:checked").length;
            if (count > 0) {
                $('.row_stock_checkbox').prop('checked', false);
                $('#inactive_stock_btn').hide();
                $('#active_stock_btn').hide();
            } else {
                $('.row_stock_checkbox').prop('checked', true);
                $('#inactive_stock_btn').show();
                $('#active_stock_btn').show();
            }
        });

        $('#active_stock_btn').on('click', function(e) {
            event.preventDefault();
            updateStockStatus('active');
        });

        $('#inactive_stock_btn').on('click', function(e) {
            event.preventDefault();
            updateStockStatus('inactive');
        });

        const swalWithBootstrapButtons = Swal.mixin({
            // customClass: {
            //     confirmButton: 'btn btn-success',
            //     cancelButton: 'btn btn-danger'
            // },
            // buttons: false
        });

        function updateStockStatus(status) {
            var token = "{{ csrf_token() }}";
            let stockNUmbers = [];
            let url = "{{ url('change/stock/status') }}";
            $("input:checkbox.row_stock_checkbox:checked").each(function() {
                stockNUmbers.push($(this).val());
            });
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    stock_numbers: stockNUmbers,
                    status: status,
                    _token: token
                },
                success: function(res) {
                    swalWithBootstrapButtons.fire(
                        'Stock!',
                        'Stock status changed successFully..',
                        'success'
                    )
                    setInterval(() => {
                        window.location.reload();
                    }, 2000);
                }
            });
        }
    </script>
@endpush
