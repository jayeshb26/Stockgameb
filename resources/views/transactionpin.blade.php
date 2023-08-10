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
                <div class="card-header d-flex justify-content-between">
                    <h6>Reset Transaction Pin</h6>
                    <div class="row text-right">
                        <a href="javascript:history.back()" class="btn btn-success"><i
                                class="fa fa-arrow-left mr-2"></i>Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('/chpin') }}">
                        @csrf
                        @if (Session::has('error'))
                            <div class="alert alert-danger" role="alert">{{ Session::get('error') }}</div>
                        @elseif(Session::has('success'))
                            <div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
                        @endif
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Old Transaction
                                Password</label>
                            <div class="col-sm-6">
                                <input type="number"
                                    class="form-control ui-autocomplete-input @error('otpass') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ Old('otpass') }}" name="otpass"
                                    autocomplete="off" placeholder="Enter Old Transaction  Password">
                                @error('otpass')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">New Transaction
                                Password</label>
                            <div class="col-sm-6">
                                <input type="number"
                                    class="form-control ui-autocomplete-input @error('ntpass') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ Old('ntpass') }}" name="ntpass"
                                    autocomplete="off" placeholder="Enter New Transaction Password">
                                @error('ntpass')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Confirm Transaction
                                Password</label>
                            <div class="col-sm-6">
                                <input type="number"
                                    class="form-control ui-autocomplete-input @error('ctpass') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ Old('ctpass') }}" name="ctpass"
                                    autocomplete="off" placeholder="Enter Confirm Transaction Password">
                                @error('ctpass')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Reset Pin</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
