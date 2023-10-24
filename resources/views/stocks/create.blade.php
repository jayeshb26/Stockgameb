@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/prismjs/prism.css') }}" rel="stylesheet" />
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h6>
                        @isset($stock)
                            Edit
                        @else
                            Add
                        @endisset Stock
                    </h6>
                    <div class="row text-right">
                        <a href="{{ url('stocks') }}" class="btn btn-success">
                            <i class="fa fa-arrow-left mr-2"></i>
                            Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @isset($stock)
                        <form method="post" action="{{ url('/stocks/' . $stock['_id']) }}">
                            @method('PUT')
                        @else
                            <form method="post" action="{{ url('/stocks') }}">
                            @endisset

                            @csrf

                            @if (Session::has('error'))
                                <div class="alert alert-danger" role="alert">{{ Session::get('error') }}
                                </div>
                            @elseif(Session::has('success'))
                                <div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
                            @endif
                            <div class="form-group d-flex">
                                <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Name</label>
                                <div class="col-sm-6">
                                    @isset($stock)
                                        <input type="text"
                                            class="form-control ui-autocomplete-input @error('name') is-invalid @enderror"
                                            id="exampleInputName1" value="{{ Old('name') ?? $stock['name'] }}" name="name"
                                            autocomplete="off" placeholder="Enter Name">
                                    @else
                                        <input type="text"
                                            class="form-control ui-autocomplete-input @error('name') is-invalid @enderror"
                                            id="exampleInputName1" value="{{ Old('name') }}" name="name" autocomplete="off"
                                            placeholder="Enter Name">
                                    @endisset


                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Symbol</label>
                                <div class="col-sm-6">
                                    @isset($stock)
                                        <input type="text"
                                            class="form-control ui-autocomplete-input @error('symbol') is-invalid @enderror"
                                            id="exampleInputUserSymbol1" value="{{ Old('symbol') ?? $stock['symbol'] }}"
                                            name="symbol" autocomplete="off" placeholder="Enter Symbol">
                                    @else
                                        <input type="text"
                                            class="form-control ui-autocomplete-input @error('symbol') is-invalid @enderror"
                                            id="exampleInputUserSymbol1" value="{{ Old('symbol') }}" name="symbol"
                                            autocomplete="off" placeholder="Enter Symbol">
                                    @endisset

                                    @error('symbol')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Market</label>
                                <div class="col-sm-6">
                                    @isset($stock)
                                        <select class="form-control @error('market') is-invalid @enderror"
                                            id="exampleInputMarket1" name="market">
                                            <option value="">Select a Market</option>
                                            @foreach ($markets as $market)
                                                <option value="{{ $market }}"
                                                    {{ (old('market') ?? $stock['market']) == $market ? 'selected' : '' }}>
                                                    {{ $market }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select class="form-control @error('market') is-invalid @enderror"
                                            id="exampleInputMarket1" name="market">
                                            <option value="">Select a Market</option>
                                            @foreach ($markets as $market)
                                                <option value="{{ $market }}"
                                                    {{ old('market') == $market ? 'selected' : '' }}>
                                                    {{ $market }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endisset

                                    @error('market')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @isset($stock)
                                <div class="form-group d-flex">
                                    <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Status</label>
                                    <div class="col-sm-6">
                                        <select name="status">
                                            <option value="1" {{ $stock->status == '1' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ $stock->status == '0' ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            @endisset
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

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/prismjs/prism.js') }}"></script>
    <script src="{{ asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#role').change(function() {
                var Role = $(this).val();
                // alert(Role);
                var uri;
                var is_f = $('#is_franchise').val();
                if (parseInt(Role) == 1) {
                    $('#s1').hide();
                    $('#comPoint').show();
                    $('#s2').hide();
                    $('#s1').closest('.form-group').css('margin-bottom', '11px');
                    uri = "{{ url('/get_data') }}";
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        url: uri,
                        type: 'POST',
                        data: {
                            role: Role,
                            is_f: is_f,
                            _token: token
                        },
                        success: function(res) {
                            $('#superDistributerId').html(res);
                            // $('#perissions').show();
                        }
                    });
                } else if (parseInt(Role) == 3) {
                    $('#s1').show();
                    $('#comPoint').show();
                    $('#s2').show();
                    $('#s1').closest('.form-group').css('margin-bottom', '11px');
                    uri = "{{ url('/get_data') }}";
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        url: uri,
                        type: 'POST',
                        data: {
                            role: Role,
                            is_f: is_f,
                            _token: token
                        },
                        success: function(res) {
                            $('#superDistributerId').html(res);
                        }
                    });
                } else if (parseInt(Role) == 5) {
                    $('#s1').show();
                    $('#comPoint').show();
                    $('#s2').show();
                    $('#s1').closest('.form-group').css('margin-bottom', '11px');
                    uri = "{{ url('/get_data') }}";
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        url: uri,
                        type: 'POST',
                        data: {
                            role: Role,
                            is_f: is_f,
                            _token: token
                        },
                        success: function(res) {
                            $('#referral').addClass('form-group');
                            $('#superDistributerId').html(res);
                        }
                    });
                } else if (parseInt(Role) == 6) {
                    $('#s1').show();
                    $('#comPoint').show();
                    $('#s2').show();
                    $('#s1').closest('.form-group').css('margin-bottom', '11px');
                    uri = "{{ url('/get_data') }}";
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        url: uri,
                        type: 'POST',
                        data: {
                            role: Role,
                            is_f: is_f,
                            _token: token
                        },
                        success: function(res) {
                            $('#referral').addClass('form-group');
                            $('#superDistributerId').html(res);
                        }
                    });
                } else if (parseInt(Role) == 7) {
                    $('#s1').show();
                    $('#comPoint').hide();
                    $('#s2').show();
                    $('#s1').closest('.form-group').css('margin-bottom', '11px');
                    uri = "{{ url('/get_data') }}";
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        url: uri,
                        type: 'POST',
                        data: {
                            role: Role,
                            is_f: is_f,
                            _token: token
                        },
                        success: function(res) {
                            $("#retailer").attr("checked", "checked");
                            // $('.superDistributerId').html(res);
                            $('#referral').addClass('form-group');
                            $('#superDistributerId').html(res);
                        }
                    });
                }
            });
            $('#superDistributerId').change(function() {
                var id = $(this).val();
                var is_f = $('#is_franchise').val();
                var token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ url('/get_distributer') }}",
                    type: 'POST',
                    data: {
                        role: id,
                        is_f: is_f,
                        _token: token
                    },
                    success: function(res) {
                        $('#referral').addClass('form-group');
                        $('#commssion').html(res);
                    }
                });
            });
        });
    </script>
@endpush
