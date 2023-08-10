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
                    <h6>point Requests</h6>
                </div>
                <div class="card-body">
                    @if (Session::has('msg'))
                        <div class="alert alert-danger" role="alert">
                            {{ Session::has('msg') ? Session::get('msg') : '' }}
                        </div>
                    @elseif(Session::has('success'))
                        <div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
                    @endif

                    <form method="post" action="{{ url('point_request_store') }}">
                        @csrf
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Points</label>
                            <div class="col-sm-6">
                                <input type="number"
                                    class="form-control ui-autocomplete-input @error('Points') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ Old('Points') }}" name="Points"
                                    autocomplete="off">
                                <input type="hidden" value="{{ Session::get('id') }}" name="id" autocomplete="off">
                                @error('Points')
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
                                    class="form-control ui-autocomplete-input @error('Comment') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ Old('Comment') }}" name="Comment"
                                    autocomplete="off">
                                @error('Comment')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
