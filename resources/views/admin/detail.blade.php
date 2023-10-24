@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title text-white mb-2">
                                    @if ($data['role'] == 'Admin')
                                        Admin
                                    @elseif($data['role']=="agent")
                                        Agent
                                    @elseif($data['role']=="premium")
                                        Premium
                                    @elseif($data['role']=="executive")
                                        Executive
                                    @elseif($data['role']=="classic")
                                        Classic
                                    @elseif($data['role']=="player")
                                        Player
                                    @endif
                                    Details
                                </h6>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <div class="align-items-baseline">
                                        <table class="table table-borderless">
                                            <tbody class="text-white">
                                                <tr>
                                                    <td style="padding:0;">
                                                        <p>Username:</p>
                                                    </td>
                                                    <td style="padding:0 0 0 10px;">
                                                        <p>&nbsp;{{ $data['userName'] }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:0;">
                                                        <p>Name:</p>
                                                    </td>
                                                    <td style="padding:0 0 0 10px;">
                                                        <p>&nbsp;{{ $data['name'] }}</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7">
                                    <div id="apexChart1" class="mt-md-3 mt-xl-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body bg-success text-white">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-1 text-white">Credit</h6>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-1">{{ number_format($data['creditPoint'], 2) }}</h3>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-1 text-white">Commission Point</h6>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-1">{{ number_format($data['commissionPoint'], 2) }}</h3>
                                </div>
                            </div>
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
                    <h6 class="card-title" style="text-transform: none !important;">
                        @if ($data['role'] == 'Admin')
                            Agent
                        @elseif($data['role']=="agent")
                            Premium
                        @elseif($data['role']=="premium")
                            Executive
                        @elseif($data['role']=="executive")
                            Classic
                        @elseif($data['role']=="classic")
                            Player
                        @endif
                        Details
                    </h6>
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SL.No</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Reffer</th>
                                    <th>Revenue</th>
                                    <th>Type</th>
                                    <th>Credit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1 @endphp
                                @foreach ($user as $value)
                                    <tr role="row" class="odd">
                                        <td class="">{{ $no++ }}</td>
                                        <td>
                                            @if ($data['role'] == 'classic')
                                                <a href="{{ url('player/detail/' . $value['_id']) }}">{{ $value['userName'] }}<i
                                                        class="mdi mdi-eye"></i></a>
                                            @else
                                                <a href="{{ url('detail/' . $value['_id']) }}">{{ $value['userName'] }}<i
                                                        class="mdi mdi-eye"></i></a>
                                            @endif
                                        </td>
                                        <td>{{ $value['name'] }}</td>
                                        <td class="sorting_1">
                                            @if ($value['referralId'] == $data['_id'])
                                                {{ $data['userName'] }}
                                            @endif
                                        </td>
                                        <td>0.00</td>
                                        <td>TN</td>
                                        <td>{{ number_format($value['creditPoint'], 2) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <div class="btn-group">
                                                    <a href="{{ url('admin/' . $value['_id'] . '/edit') }}" type="button"
                                                        class="btn btn-outline-info" title="Edit user"><i
                                                            class="mdi mdi-pencil-box" style="font-size:20px;"></i></a>

                                                    @if (Session::get('role') == 'agent' || Session::get('role') == 'premium')
                                                        <a href="{{ url('transfercredit/' . $value['_id']) }}"
                                                            class="btn btn-outline-success" title="Transfer Credit"><i
                                                                class="mdi mdi-package-up" style="font-size:20px;"></i></a>
                                                        <a href="{{ url('adjustcredit/' . $value['_id']) }}"
                                                            class="btn btn-outline-warning" title="Adjust Credit"><i
                                                                class="mdi mdi-package-down"
                                                                style="font-size:20px;"></i></a>
                                                    @endif
                                                    @if ($value['isActive'] == 1)
                                                        <a href="{{ url('banuser/' . $value['_id'] . '/' . $value['isActive']) }}"
                                                            class="btn btn-outline-success" title="Ban User"><i
                                                                class="mdi mdi-close-box" style="font-size:20px;"></i></a>
                                                    @elseif($value['isActive']==0)
                                                        <a href="{{ url('banuser/' . $value['_id'] . '/0') }}"
                                                            class="btn btn-outline-danger" title="UnBan User"><i
                                                                class="mdi mdi-checkbox-marked"
                                                                style="font-size:20px;"></i></a>
                                                    @endif
                                                    @if (Session::get('role') == 'Admin')
                                                        <a href="{{ url('admin/delete/' . $value['_id']) }}"
                                                            class="btn btn-outline-danger delete-confirm" title="delete"><i
                                                                class="mdi mdi-delete" style="font-size:20px;"></i></a>
                                                    @endif
                                                </div>
                                            </div>
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
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
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
    </script>
@endpush
