@extends('layout.master2')

@section('content')
<div class="page-content d-flex align-items-center justify-content-center">
    <div class="row w-100 mx-0 auth-page">
        <div class="col-md-8 col-xl-6 mx-auto">
            <div class="card">
                <div class="row">
                    <div class="col-md-12">
                        <div class="auth-form-wrapper pt-5">
                            <img src="{{ url('favicon.ico') }}" class="img-responsive mx-auto d-block mb-3"
                                style="width:300px;" />
                            <a href="#" class="noble-ui-logo d-block mb-2 text-center">Diamond<span>Coupon</span></a>
                            <h5 class="text-muted font-weight-normal mb-4 text-center">Welcome Coupon Download Here..
                                and
                                <a href="{{ url('/login') }}">Login</a>
                            </h5>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-center px-5 py-5">
                        <a href="{{ url('assets/DiamondCoupon.rar') }}"><img src="{{ url('assets/windowsdwn.png') }}"
                                class="img-responsive"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection