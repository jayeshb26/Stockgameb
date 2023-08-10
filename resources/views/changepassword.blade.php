@extends('layout.master')

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
                    <h6>Change Password</h6>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('/chpassword') }}">
                        @csrf
                        @if (Session::has('error'))
                            <div class="alert alert-danger" role="alert">{{ Session::get('error') }}</div>
                        @elseif(Session::has('success'))
                            <div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
                        @endif
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Old Password</label>
                            <div class="col-sm-6">
                                <input type="text"
                                    class="form-control ui-autocomplete-input @error('opass') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ Old('opass') }}" name="opass" autocomplete="off"
                                    placeholder="Enter Old Password">
                                @error('opass')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">New Password</label>
                            <div class="col-sm-6">
                                <input type="text"
                                    class="form-control ui-autocomplete-input @error('npass') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ Old('npass') }}" name="npass" autocomplete="off"
                                    placeholder="Enter New Password">
                                @error('npass')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Confirm Password</label>
                            <div class="col-sm-6">
                                <input type="text"
                                    class="form-control ui-autocomplete-input @error('cpass') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ Old('cpass') }}" name="cpass" autocomplete="off"
                                    placeholder="Enter Confirm Password">
                                @error('cpass')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Change Passowrd</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
