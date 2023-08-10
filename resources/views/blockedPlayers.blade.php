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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Blocked Players</h6>
        {{-- <p class="card-description">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> --}}
        <div class="table-responsive">
          <table id="dataTableExample" class="table table-bordered">
            <thead>
              <tr>
                <th>SL.No</th>
                <th>Username</th>
                <th>Name</th>
                <th>Substitute</th>
                <th>Credit</th>
                <th>Created Date</th>
              </tr>
            </thead>
            <tbody>
                @php
                    $SR_No = 1;
                @endphp
              @foreach($data as $value)
                @if($value['role'] != 'Admin')
                  <tr role="row" class="odd">
                      <td class=""><?= $SR_No++; ?></td>
                      <td>{{$value['userName']}}</td>
                      <td>{{$value['name']}}</td>
                      <td>{{$value['referral']}}</td>
                      <td>{{number_format($value['creditPoint'],2)}}</td>
                      <td>{{\Carbon\Carbon::parse($value['createdAt'])->format('d-m-Y h:i:s A') }}</td>
                  </tr>
                @endif
              @endforeach
                {{-- <tr role="row" class="odd">
                    <td class="">1306</td>
                    <td><a href="{{ url('detail/168')}}">100653 <i class="mdi mdi-eye"></i></a></td>
                    <td>Sham bhau </td>
                    <td class="sorting_1">500127</td>
                    <td>0.00</td>
                    <td>TN</td>
                    <td>46.00</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ url('users/edit/1306')}}" type="button" class="btn btn-outline-info" title="Edit user"><i class="mdi mdi-pencil-box" style="font-size:20px;"></i></a>
                            <a href="http://www.slcoupon.com/transfercredit/1306" class="btn btn-outline-success" title="Transfer Credit"><i class="mdi mdi-package-up" style="font-size:20px;"></i></a>
                            <a href="http://www.slcoupon.com/adjustcredit/1306" class="btn btn-outline-warning" title="Adjust Credit"><i class="mdi mdi-package-down" style="font-size:20px;"></i></a>
                            <a href="http://www.slcoupon.com/banuser/1306/1" class="btn btn-outline-success" title="Ban User"><i class="mdi mdi-checkbox-marked" style="font-size:20px;"></i></a>
                        </div>
                    </td>
                </tr> --}}
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
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  {{-- <script src="{{ asset('assets/js/sweet-alert.js') }}"></script> --}}
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script type="text/javascript">
    $('.delete-confirm').on('click', function (event) {
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