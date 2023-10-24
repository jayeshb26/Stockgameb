@extends('layout.master2')

@push('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    {!! NoCaptcha::renderJs() !!}
@endpush

@push('customscripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    {!! NoCaptcha::renderJs() !!}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush

@section('content')
    <div class="page-content d-flex align-items-center justify-content-center">
        <div class="row w-100 mx-0 auth-page">
            <div class="col-md-6 col-xl-4 mx-auto">
                <div class="card" style="border: 2px solid #09c35a">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="auth-form-wrapper px-5 py-5">
                                <a href="#" class="noble-ui-logo d-block mb-2 text-center">StockSkill</a>
                                <h5 class="text-muted font-weight-normal text-center">Welcome back! Log in to your
                                    account.</h5>
                                @if (Session::has('msg'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ Session::has('msg') ? Session::get('msg') : '' }}
                                    </div>
                                @endif
                                <form class="forms-sample" method="post" action="{{ url('/login_custom') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Username</label>
                                        <input type="text" class="form-control @error('userName') is-invalid @enderror"
                                            name="userName" value="{{ Old('userName') }}" id="exampleInputEmail1"
                                            placeholder="Username">
                                        @error('userName')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" id="exampleInputPassword1" autocomplete="current-password"
                                            placeholder="Password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    {{-- <div class="form-group">
                                        <div class="g-recaptcha" data-sitekey="6Lf4GSMcAAAAAF25CFuUqvh1vzpNkGSMrYX0gZJD">
                                        </div>
                                        @error('g-recaptcha-response')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div> --}}
                                    <div class="col-md-12 p-0 d-flex">
                                        <div class="col-md-6 p-0"><button type="submit"
                                                class="btn btn-primary mr-2 mb-2 mb-md-0">Login</button>
                                        </div>
                                        {{-- <div class="col-md-6 p-0">
                                            <a href="{{ url('assets/Diamond Coupon_24_08_2021.exe') }}"
                                                class="d-flex float-right align-content-end"><img
                                                    src="{{ url('assets/windowsdwn.png') }}" class="img-responsive"
                                                    width="100px"></a>
                                        </div> --}}
                                    </div>
                                    {{-- <div class="col-md-12 p-0 mt-2">
                                        <span class="d-flex float-right align-content-end">Update : 24/08/2021</span>
                                    </div> --}}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
