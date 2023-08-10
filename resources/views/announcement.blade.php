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
                    <h6>Announcement</h6>
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

                    <form method="post" action="{{ url('announcements/') }}">
                        @csrf
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Announcements</label>
                            <div class="col-sm-6">
                                <input type="tetx"
                                    class="form-control ui-autocomplete-input @error('amount') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ $data['announcement'] }}" name="amount"
                                    autocomplete="off">
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Announcement</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
