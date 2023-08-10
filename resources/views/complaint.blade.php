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
@if(Session::has('msg'))
<div class="alert alert-danger" role="alert">
  {{ Session::has('msg') ? Session::get("msg") : '' }}
</div>
@elseif(Session::has('success'))
<div class="alert alert-success" role="alert">{{Session::get("success")}}</div>
@endif


<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-header d-flex">
        <h5 class="col-sm-10 mt-2">Complaint</h5>
        <div class="col-sm-2 text-right">
          <a href="{{ url('complaintAll')}}" class="btn btn-outline-danger delete-all" title="delete">Delete All</a>
        </div>
      </div>
      <div class="card-body">
        {{-- <p class="card-description">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> --}}
        <div class="table-responsive">
          <table id="dataTableExample" class="table table-bordered">
            <thead>
              <tr>
                <th>SR_No</th>
                <th>Username</th>
                <th>Title</th>
                <th>Content</th>
                <th>Created Date</th>
                <th>Status</th>
                <th>Action</th>
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
                <td>
                  @if($value['title']=="Facing issue with printer")
                  प्रिंटरमध्ये समस्या आहे
                  @elseif($value['title']=="Facing issue with scanner")
                  स्कॅनरमध्ये समस्या आहे
                  @elseif($value['title']=="Facing issue with Result")
                  निकालात समस्या आहे
                  @elseif($value['title']=="Facing Issue with Barcode")
                  बारकोडमध्ये समस्या आहे
                  @elseif($value['title']=="Facing issue with software")
                  सॉफ्टवेअरमध्ये समस्या आहे
                  @endif
                </td>
                <td>
                  @if($value['content']=="")
                  --
                  @else
                  {{ $value['content'] }}
                  @endif
                </td>
                <td>{{date("d-m-Y h:i:s A",strtotime($value['createdAt'])) }}</td>
                <td>
                  @if($value['status']==0)
                  <a href="{{ url('complaint/'.$value['status'].'/'.$value['_id']) }}"><span
                      class="badge badge-danger">Pending</span></a>
                  @else
                  <a href="{{ url('complaint/'.$value['status'].'/'.$value['_id']) }}"><span
                      class="badge badge-success">Solve</span></a>
                  @endif
                </td>
                <td>
                  <div class="btn-group">
                    <a href="{{ url('complaint/delete/'.$value['_id'])}}" class="btn btn-outline-danger delete-confirm"
                      title="delete"><i class="mdi mdi-delete" style="font-size:20px;"></i></a>
                  </div>
                </td>
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
                  <a href="{{ url('users/edit/1306')}}" type="button" class="btn btn-outline-info" title="Edit user"><i
                      class="mdi mdi-pencil-box" style="font-size:20px;"></i></a>
                  <a href="http://www.slcoupon.com/transfercredit/1306" class="btn btn-outline-success"
                    title="Transfer Credit"><i class="mdi mdi-package-up" style="font-size:20px;"></i></a>
                  <a href="http://www.slcoupon.com/adjustcredit/1306" class="btn btn-outline-warning"
                    title="Adjust Credit"><i class="mdi mdi-package-down" style="font-size:20px;"></i></a>
                  <a href="http://www.slcoupon.com/banuser/1306/1" class="btn btn-outline-success" title="Ban User"><i
                      class="mdi mdi-checkbox-marked" style="font-size:20px;"></i></a>
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
<script type="text/javascript">
  $('.delete-all').on('click', function (event) {
      event.preventDefault();
      const url = $(this).attr('href');
      const swalWithBootstrapButtons = Swal.mixin({
        input: 'text',
        confirmButtonText: 'Done',
        showCancelButton: true,
        progressSteps: []
      }).queue([
        {
          title: 'Are You Sure All Complaint Delete',
          text: 'Admin Password'
        },
      ]).then((result) => {
        if (result.value) {
          window.location.href = url+"/"+result.value;
        }
      })
    });
</script>
@endpush