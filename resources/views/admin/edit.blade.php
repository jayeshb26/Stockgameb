@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/prismjs/prism.css') }}" rel="stylesheet" />
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
                    <h6>Edit User</h6>
                </div>
                <div class="card-body">
                    @if (Session::has('msg'))
                        <div class="alert alert-danger" role="alert">
                            {{ Session::has('msg') ? Session::get('msg') : '' }}
                        </div>
                    @elseif(Session::has('success'))
                        <div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
                    @endif

                    @php
                        if (request()->segment(1) == 'retailer') {
                            $role = 'retailer';
                        } elseif (request()->segment(1) == 'distributer') {
                            $role = 'distributer';
                        } elseif (request()->segment(1) == 'super') {
                            $role = 'superDistributer';
                        } elseif (request()->segment(1) == 'admin') {
                            $role = 'admin';
                        }
                    @endphp
                    <form method="post" action="{{ url($role . '/' . $edata['_id']) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- @if (Session::has('error'))
                            <div class="alert alert-danger" role="alert">{{Session::get("error")}}</div>
                        @elseif(Session::has('success'))
                            <div class="alert alert-success" role="alert">{{Session::get("success")}}</div>
                        @endif --}}
                        {{-- <div class="form-group d-flex">
                        <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Username</label>
                        <div class="col-sm-6"> --}}
                        <input type="hidden"
                            class="form-control ui-autocomplete-input @error('username') is-invalid @enderror"
                            id="exampleInputUsername1" value="{{ $edata['userName'] }}" name="username" autocomplete="off"
                            placeholder="Enter Username">
                        {{-- @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Name</label>
                            <div class="col-sm-6">
                                <input type="text"
                                    class="form-control ui-autocomplete-input @error('name') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ $edata['name'] }}" name="name" autocomplete="off"
                                    placeholder="Enter Name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @if (Session::get('role') == 'Admin')
                            <div class="form-group d-flex">
                                <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Role Management</label>
                                <div class="col-sm-6">
                                    <div class="form-group mb-2">
                                        <select class="form-control" name="role" id="role">
                                            <option selected disabled>Select Role</option>
                                            @if (Session::get('role') == 'Admin' || Session::get('role') == 'agent' || Session::get('role') == 'premium' || Session::get('role') == 'executive' || Session::get('role') == 'classic')
                                                @if ($edata['is_franchise'] == 1)
                                                    <option value="3" {{ $edata['role'] == 'premium' ? 'selected' : '' }}>
                                                        f_Premium
                                                    </option>
                                                    <option value="5"
                                                        {{ $edata['role'] == 'executive' ? 'selected' : '' }}>
                                                        f_Executive</option>
                                                    <option value="6" {{ $edata['role'] == 'classic' ? 'selected' : '' }}>
                                                        f_Classic
                                                    </option>
                                                    <option value="7" {{ $edata['role'] == 'player' ? 'selected' : '' }}>
                                                        f_player
                                                    </option>
                                                @else
                                                    <option value="1" {{ $edata['role'] == 'agent' ? 'selected' : '' }}>
                                                        Agent
                                                    </option>
                                                    <option value="3"
                                                        {{ $edata['role'] == 'premium' ? 'selected' : '' }}>Premium
                                                    </option>
                                                    <option value="5"
                                                        {{ $edata['role'] == 'executive' ? 'selected' : '' }}>
                                                        Executive</option>
                                                    <option value="6"
                                                        {{ $edata['role'] == 'classic' ? 'selected' : '' }}>Classic
                                                    </option>
                                                    <option value="7" {{ $edata['role'] == 'player' ? 'selected' : '' }}>
                                                        player
                                                    </option>
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                    @error('role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        @if (Session::get('role') == 'Admin')
                            @if ($edata['role'] == 'Admin' || Session::get('role') == 'agent')
                                <input type='hidden' class='form-control ui-autocomplete-input' id='exampleInputUsername1'
                                    value='{{ $edata['referralId'] }}' name='referralId' autocomplete='off'
                                    placeholder='Enter Firm Name'>
                                <div class="form-group d-flex" id="referral2">
                                    <label class="col-sm-2 offset-lg-1 text-right control-label mt-2"
                                        id="s1">Substitute</label>
                                    <div class="col-sm-6" id="s2">
                                        <div class="form-group mb-2">
                                            <select class="form-control superDistributerId" name="superDistributerId"
                                                id="superDistributerId">
                                                <option selected disabled>Select Super Distributor</option>
                                                @foreach ($udata as $value)
                                                    @if ($edata['referralId'] == $value['_id'])
                                                        <option value="{{ $edata['referralId'] }}"
                                                            {{ $edata['referralId'] == $value['_id'] ? 'selected' : '' }}>
                                                            {{ $value['userName'] . ' ' . $value['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('superDistributer')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @elseif($edata['role']=="premium")
                                <input type='hidden' class='form-control ui-autocomplete-input' id='exampleInputUsername1'
                                    value='{{ $edata['referralId'] }}' name='referralId' autocomplete='off'
                                    placeholder='Enter Firm Name'>
                                <div class="form-group d-flex" id="referral2">
                                    <label class="col-sm-2 offset-lg-1 text-right control-label mt-2"
                                        id="s1">Substitute</label>
                                    <div class="col-sm-6" id="s2">
                                        <div class="form-group mb-2">
                                            <select class="form-control superDistributerId" name="superDistributerId"
                                                id="superDistributerId">
                                                <option selected disabled>Select Super Distributor</option>
                                                @foreach ($udata as $value)
                                                    @if ($edata['referralId'] == $value['_id'])
                                                        <option value="{{ $edata['referralId'] }}"
                                                            {{ $edata['referralId'] == $value['_id'] ? 'selected' : '' }}>
                                                            {{ $value['userName'] . ' ' . $value['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('superDistributer')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @elseif($edata['role']=="executive")
                                <div class="form-group d-flex" id="referral2">
                                    <label class="col-sm-2 offset-lg-1 text-right control-label mt-2"
                                        id="s1">Substitute</label>
                                    <div class="col-sm-6" id="s2">
                                        <div class="form-group mb-2">
                                            <select class="form-control superDistributerId" name="superDistributerId"
                                                id="superDistributerId">
                                                <option selected disabled>Select Super Distributor</option>
                                                @foreach ($udata as $value)
                                                    @if ($edata['referralId'] == $value['_id'])
                                                        <option value="{{ $edata['referralId'] }}"
                                                            {{ $edata['referralId'] == $value['_id'] ? 'selected' : '' }}>
                                                            {{ $value['userName'] . ' ' . $value['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('superDistributer')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @elseif($edata['role']=="classic")
                                <div class="form-group d-flex" id="referral2">
                                    <label class="col-sm-2 offset-lg-1 text-right control-label mt-2"
                                        id="s1">Substitute</label>
                                    <div class="col-sm-6" id="s2">
                                        <div class="form-group mb-2">
                                            <select class="form-control superDistributerId" name="superDistributerId"
                                                id="superDistributerId">
                                                <option selected disabled>Select Super Distributor</option>
                                                @foreach ($udata as $value)
                                                    @if ($edata['referralId'] == $value['_id'])
                                                        <option value="{{ $edata['referralId'] }}"
                                                            {{ $edata['referralId'] == $value['_id'] ? 'selected' : '' }}>
                                                            {{ $value['userName'] . ' ' . $value['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('superDistributer')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @elseif($edata['role']=="player")
                                <div class="form-group d-flex" id="referral2">
                                    <label class="col-sm-2 offset-lg-1 text-right control-label mt-2"
                                        id="s1">Substitute</label>
                                    <div class="col-sm-6" id="s2">
                                        <div class="form-group mb-2">
                                            <select class="form-control superDistributerId" name="superDistributerId"
                                                id="superDistributerId">
                                                <option selected disabled>Select Super Distributor</option>
                                                @foreach ($udata as $value)
                                                    @if ($edata['referralId'] == $value['_id'])
                                                        <option value="{{ $edata['referralId'] }}"
                                                            {{ $edata['referralId'] == $value['_id'] ? 'selected' : '' }}>
                                                            {{ $value['userName'] . ' ' . $value['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('superDistributer')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        @else
                            @if ($edata['role'] == 'agent' || $edata['role'] == 'premium' || $edata['role'] == 'executive' || $edata['role'] == 'classic' || $edata['role'] == 'player')
                                <input type='hidden' class='form-control ui-autocomplete-input' id='exampleInputUsername1'
                                    value='{{ $edata['referralId'] }}' name='referralId' autocomplete='off'
                                    placeholder='Enter Firm Name'>
                                <input type='hidden' class='form-control ui-autocomplete-input' id='exampleInputUsername1'
                                    value='{{ $edata['role'] }}' name='role' autocomplete='off'
                                    placeholder='Enter Firm Name'>
                            @endif
                        @endif

                        <div class="d-flex" id="referral">

                        </div>

                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Password</label>
                            <div class="col-sm-6">
                                <input type="text"
                                    class="form-control ui-autocomplete-input @error('password') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ $edata['password'] }}" name="password"
                                    autocomplete="off" placeholder="Enter Password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Transaction Pin</label>
                            <div class="col-sm-6">
                                <input type="number"
                                    class="form-control ui-autocomplete-input @error('transactionPin') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ $edata['transactionPin'] }}"
                                    name="transactionPin" autocomplete="off" placeholder="Enter Transaction Pin">
                                @error('transactionPin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Commission %</label>
                            <div class="col-sm-6">
                                <input type="text"
                                    class="form-control ui-autocomplete-input @error('commissionPercentage') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ $edata['commissionPercentage'] }}"
                                    name="commissionPercentage" autocomplete="off" placeholder="Enter CommissionPercentage">
                                @error('commissionPercentage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @if($edata['profile_pic'])
                            <div class="form-group d-flex">
                                <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Current Profile Pic</label>
                                <div class="col-sm-6">
                                    <img src="{{asset('storage/'.$edata['profile_pic'])}}" alt="Italian Trulli" height="150px" width="150px">
                                </div>
                            </div>
                        @endif

                        <div class="form-group d-flex">
                            
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Profile Pic</label>
                            <div class="col-sm-6">
                                <input type="file" class="form-control ui-autocomplete-input @error('profile_pic') is-invalid @enderror"
                                    id="inputProfilePic" value="{{ Old('profile_pic') }}"
                                    name="profile_pic" autocomplete="off" placeholder="Enter Commission %">
                                @error('profile_pic')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if($edata['pancard'])
                            <div class="form-group d-flex">
                                <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Current Pancard</label>
                                <div class="col-sm-6">
                                    <img src="{{asset('storage/'.$edata['pancard'])}}" alt="Italian Trulli" height="150px" width="150px">
                                </div>
                            </div>
                        @endif
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Pancard Image</label>
                            <div class="col-sm-6">
                                <input type="file" class="form-control ui-autocomplete-input @error('pancard') is-invalid @enderror"
                                    id="inputPancard" value="{{ Old('pancard') }}"
                                    name="pancard" autocomplete="off" placeholder="Enter Commission %">
                                @error('pancard')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if($edata['pancard'])
                            <div class="form-group d-flex">
                                <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Current Adhaar card</label>
                                <div class="col-sm-6">
                                    <img src="{{asset('storage/'.$edata['adharcard'])}}" alt="Italian Trulli" height="150px" width="150px">
                                </div>
                            </div>
                        @endif
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Adhaar card Image</label>
                            <div class="col-sm-6">
                                <input type="file" class="form-control ui-autocomplete-input @error('adharcard') is-invalid @enderror"
                                    id="inputAdharcard" value="{{ Old('adharcard') }}"
                                    name="adharcard" autocomplete="off" placeholder="Enter Commission %">
                                @error('adharcard')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex" id="perissions">
                            {{-- <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Page Permission</label> --}}
                            <div class="col-sm-3">
                                {{-- <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="add_user">
                                        <input type="checkbox" class="form-check-input" name="permission[]" value="add_user"
                                            {{ isset($edata['permissions']['add_user']) == true ? 'checked' : '' }}>
                                        Add User
                                    </label>
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="view_user">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="view_user"
                                            {{ isset($edata['permissions']['view_user']) == true ? 'checked' : '' }}>
                                        View User
                                    </label>
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="superdistributer">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="superdistributer"
                                            {{ isset($edata['permissions']['superdistributer']) == true ? 'checked' : '' }}>
                                        Superdistributor
                                    </label>
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="distributer">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="distributer"
                                            {{ isset($edata['permissions']['distributer']) == true ? 'checked' : '' }}>
                                        Distributor
                                    </label>
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="retailer">
                                        <input type="checkbox" class="form-check-input" name="permission[]" value="retailer"
                                            {{ isset($edata['permissions']['retailer']) == true ? 'checked' : '' }}>
                                        Retailer
                                    </label>
                                </div> --}}
                            </div>
                            <div class="col-sm-3">
                                {{-- <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="winningPercent">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="winningPercent"
                                            {{ isset($edata['permissions']['winningPercent']) == true ? 'checked' : '' }}>
                                        Winning %
                                    </label>
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="winhistory">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="winhistory">
                                        Win History
                                    </label>
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="winbyadmin">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="winbyadmin"
                                            {{ isset($edata['permissions']['winbyadmin']) == true ? 'checked' : '' }}>
                                        Win By Admin
                                    </label>
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="announcement">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="announcement"
                                            {{ isset($edata['permissions']['announcement']) == true ? 'checked' : '' }}>
                                        Announcement
                                    </label>
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="complaint">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="complaint"
                                            {{ isset($edata['permissions']['complaint']) == true ? 'checked' : '' }}>
                                        Complaint
                                    </label>
                                </div> --}}
                                <input type="hidden" class="form-check-input" name="permission[]" id="retailer"
                                    value="retai">
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
                if (parseInt(Role) == 1) {
                    $('#s1').hide();
                    $('#s2').hide();
                    $('#s1').closest('.form-group').css('margin-bottom', '0px');
                    uri = "{{ url('/get_data') }}";
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        url: uri,
                        type: 'POST',
                        data: {
                            role: Role,
                            _token: token
                        },
                        success: function(res) {
                            $('#referral').html(res);
                            $('#perissions').show();
                        }
                    });
                } else if (parseInt(Role) == 3) {
                    $('#s1').hide();
                    $('#s2').hide();
                    $('#s1').closest('.form-group').css('margin-bottom', '0px');
                    uri = "{{ url('/get_data') }}";
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        url: uri,
                        type: 'POST',
                        data: {
                            role: Role,
                            _token: token
                        },
                        success: function(res) {
                            $('#referral').html(res);
                        }
                    });
                } else if (parseInt(Role) == 5) {
                    $('#s1').hide();
                    $('#s2').hide();
                    $('#s1').closest('.form-group').css('margin-bottom', '0px');
                    uri = "{{ url('/get_data') }}";
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        url: uri,
                        type: 'POST',
                        data: {
                            role: Role,
                            _token: token
                        },
                        success: function(res) {
                            $('#referral').addClass('form-group');
                            $('#referral').html(res);
                        }
                    });
                } else if (parseInt(Role) == 6) {
                    $('#s1').hide();
                    $('#s2').hide();
                    $('#s1').closest('.form-group').css('margin-bottom', '0px');
                    uri = "{{ url('/get_data') }}";
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        url: uri,
                        type: 'POST',
                        data: {
                            role: Role,
                            _token: token
                        },
                        success: function(res) {
                            $('#referral').addClass('form-group');
                            $('#referral').html(res);
                        }
                    });
                } else if (parseInt(Role) == 7) {
                    $('#s1').show();
                    $('#s2').show();
                    $('#s1').closest('.form-group').css('margin-bottom', '11px');
                    uri = "{{ url('/get_data') }}";
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        url: uri,
                        type: 'POST',
                        data: {
                            role: Role,
                            _token: token
                        },
                        success: function(res) {
                            $("#retailer").attr("checked", "checked");
                            $('.superDistributerId').html(res);
                            $('#referral1').html(res);
                        }
                    });
                }
                $('#superDistributerId').change(function() {
                    var id = $(this).val();
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ url('/get_distributer') }}",
                        type: 'POST',
                        data: {
                            role: id,
                            _token: token
                        },
                        success: function(res) {
                            $('#referral').addClass('form-group');
                            $('#referral').html(res);
                        }
                    });
                });
            });
        });
    </script>
@endpush
