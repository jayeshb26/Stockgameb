@extends('layout.master')


@push('plugin-styles')
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
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
                <div class="card-header">
                    <h6>Subtract Balance</h6>
                </div>
                <div class="card-body">
                    @if (Session::has('msg'))
                        <div class="alert alert-danger" role="alert">
                            {{ Session::has('msg') ? Session::get('msg') : '' }}
                        </div>
                    @elseif(Session::has('success'))
                        <div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
                    @endif

                    <input type="hidden" name="userid" value="{{ $data['_id'] }}">
                    <form action="{{ url('adjustcredit/' . $data['_id']) }}" id="form">
                        @csrf
                        <div class="form-group d-flex">
                            <div class="col-sm-3 offset-lg-3">
                                <h4 class="breadcrumb bg-light">User: {{ $data['userName'] }}</h4><br>
                                <h4 class="breadcrumb bg-light">Points: {{ number_format($data['creditPoint'], 2) }}
                                </h4>
                            </div>
                            <div class="col-sm-3">
                                <h4 class="breadcrumb bg-light">Points available:
                                    {{ number_format(Session::get('creditPoint'), 2) }}</h4>
                                <br>
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Points Adjust</label>
                            <div class="col-sm-6">
                                <input type="number"
                                    class="form-control ui-autocomplete-input @error('amount') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ Old('amount') }}" name="amount"
                                    autocomplete="off">
                                <input type="hidden" value="{{ $data['_id'] }}" name="id" autocomplete="off">
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Transaction
                                Password</label>
                            <div class="col-sm-6">
                                <input type="password"
                                    class="form-control ui-autocomplete-input @error('password') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ Old('password') }}" name="password"
                                    autocomplete="off">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Comment</label>
                            <div class="col-sm-6">
                                <input type="text"
                                    class="form-control ui-autocomplete-input @error('comment') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ Old('comment') }}" name="comment"
                                    autocomplete="off">
                                @error('comment')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Adjust Points</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            event.preventDefault();
            var token = $('input[name="_token"]').val();
            var id = $('input[name="userid"]').val();
            var url = $(this).attr('action');
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
            })
            var amount = $('input[name="amount"]').val();
            swalWithBootstrapButtons.fire({
                title1: '<span style="color:red;">Are you sure?</span>',
                text: "You adjust to Amount : " + amount + " (" + convert(amount) +
                    ")" +
                    " And Your Balance : " + {{ Session::get('creditPoint') }},
                icon: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'ml-2',
                confirmButtonText: 'Yes, Transfer it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            amount: $('input[name="amount"]').val(),
                            password: $('input[name="password"]').val(),
                            comment: $('input[name="comment"]').val(),
                            _token: token
                        },
                        success: function(res) {
                            window.location.reload();
                            // $('#perissions').show();
                        }
                    });
                    swalWithBootstrapButtons.fire(
                        'Transfer!',
                        'Point Transfer SuccessFully..',
                        'success'
                    )
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Point Not Transfer SuccessFully..',
                        'error'
                    )
                }
            })
        });
    </script>
@endpush
