@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/prismjs/prism.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
    <div class="container">
        <a href="{{ route('markets.create') }}" class="btn btn-primary mb-3">Create Market</a>
        <div class="card card-columns pr-2 pl-2">
            <h1 class="card-title my-4 ml-2">Markets</h1>
            <div class="table-responsive my-2">
                <table id="marketsTable" class="table table-bordered ml-2 my-4">
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Game Name</th>
                            <th>Market</th>
                            <th>Week Start-Close Time </th>
                            <th>Buckets (&percnt;)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($markets as $market)
                            <tr>
                                <td>{{ $market->name }}</td>
                                <td>{{ $market->gamename }}</td>
                                <td>{{ $market->market }}</td>
                                <td> <b>Monday-</b>
                                    {{ $market->mon_start_time === '00:00' ? ' Holiday' : $market->mon_start_time }} -
                                    {{ $market->mon_close_time === '00:00' ? ' Holiday' : $market->mon_close_time }} </br>
                                    <b>Tuesday-</b>
                                    {{ $market->tue_start_time === '00:00' ? ' Holiday' : $market->tue_start_time }} -
                                    {{ $market->tue_close_time === '00:00' ? ' Holiday' : $market->tue_close_time }} </br>
                                    <b>Wednesday-</b>
                                    {{ $market->wed_start_time === '00:00' ? ' Holiday' : $market->wed_start_time }} -
                                    {{ $market->wed_close_time === '00:00' ? ' Holiday' : $market->wed_close_time }} </br>
                                    <b>Thursday-</b>
                                    {{ $market->thu_start_time === '00:00' ? ' Holiday' : $market->thu_start_time }} -
                                    {{ $market->thu_close_time === '00:00' ? ' Holiday' : $market->thu_close_time }} </br>
                                    <b>Friday-</b>
                                    {{ $market->fri_start_time === '00:00' ? ' Holiday' : $market->fri_start_time }} -
                                    {{ $market->fri_close_time === '00:00' ? ' Holiday' : $market->fri_close_time }} </br>
                                    <b>Saturday-</b>
                                    {{ $market->sat_start_time === '00:00' ? ' Holiday' : $market->sat_start_time }} -
                                    {{ $market->sat_close_time === '00:00' ? ' Holiday' : $market->sat_close_time }} </br>
                                    <b>Sunday-</b>
                                    {{ $market->sun_start_time === '00:00' ? ' Holiday' : $market->sun_start_time }} -
                                    {{ $market->sun_close_time === '00:00' ? ' Holiday' : $market->sun_close_time }} </br>
                                </td>

                                <td>
                                    <b>Bucket : </b>{{ $market->bucket }} <br>
                                    <b>Bucket-3 : </b>{{ $market->bucket3 }}<br>
                                    <b>Bucket-5 : </b> {{ $market->bucket5 }}<br>
                                </td>
                                <td>
                                    @if ($market->status === 'Active')
                                        <button class="btn btn-success">{{ $market->status }}</button>
                                    @else
                                        <button class="btn btn-danger">{{ $market->status }}</button>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('markets.edit', $market->id) }}"
                                        class="btn btn-primary btn-sm">Edit</a>
                                    <form class="delete-confirm" action="{{ route('markets.destroy', $market->id) }}"
                                        method="POST" style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
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
    <script>
        $(document).ready(function() {
            $('#marketsTable').DataTable();
        });
    </script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    {{--  <script type="text/javascript">
        $('.delete-confirm').on('click', function(event) {
            event.preventDefault();
            const url = $(this).attr('href');
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
            });
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                icon: 'warning',
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonClass: 'ml-2',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true,
                position: 'center',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                    swalWithBootstrapButtons.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    );
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    );
                }
            });
        });
    </script>  --}}
@endpush
