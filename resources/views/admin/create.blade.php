@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/prismjs/prism.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        @if (Request::segment(1) == 'agents')
                            @if (Session::get('role') == 'Admin' || Session::get('role') == 'subadmin')
                                <a href="{{ url('/agents/add_agent') }}" class="btn btn-success"><i
                                        class="fa fa-plus"></i>
                                    Add Agent</a>
                            @endif
                            @if (Session::get('role') == 'Admin' || Session::get('role') == 'subadmin' || Session::get('role') == 'agent')
                                {{-- <a href="{{ url('/agents/add_premium') }}" class="btn btn-success"><i
                                        class="fa fa-plus"></i>
                                    Add Premium</a> --}}
                            @endif
                            @if (Session::get('role') == 'premium' || Session::get('role') == 'subadmin' || Session::get('role') == 'Admin' || Session::get('role') == 'agent')
                                <a href="{{ url('/agents/add_executive') }}" class="btn btn-success"><i
                                        class="fa fa-plus"></i>
                                    Add Distributer</a>
                            @endif
                            @if (Session::get('role') == 'executive' || Session::get('role') == 'subadmin' || Session::get('role') == 'premium' || Session::get('role') == 'Admin' || Session::get('role') == 'agent')
                                <a href="{{ url('/agents/add_agent') }}" class="btn btn-success"><i
                                        class="fa fa-plus"></i>
                                    Add Agent</a>
                            @endif
                            @if (Session::get('role') == 'classic' || Session::get('role') == 'subadmin' || Session::get('role') == 'executive' || Session::get('role') == 'premium' || Session::get('role') == 'Admin' || Session::get('role') == 'agent')
                                <a href="{{ url('/agents/add_player') }}" class="btn btn-success"><i
                                        class="fa fa-plus"></i>
                                    Add Player</a>
                            @endif
                        @else
                            @if (Session::get('role') == 'Admin' || Session::get('role') == 'subadmin')
                            {{-- <a href="{{ url('/Franchise/add_premium') }}" class="btn btn-success"><i
                                        class="fa fa-plus"></i>
                                    Add Premium</a> --}}
                            @endif
                            @if (Session::get('role') == 'premium' || Session::get('role') == 'subadmin' || Session::get('role') == 'Admin')
                                <a href="{{ url('/Franchise/add_distributer') }}" class="btn btn-success"><i
                                        class="fa fa-plus"></i>
                                    Add Distributer</a>
                            @endif
                            @if (Session::get('role') == 'executive' || Session::get('role') == 'subadmin' || Session::get('role') == 'premium' || Session::get('role') == 'Admin')
                                <a href="{{ url('/Franchise/add_agent') }}" class="btn btn-success"><i
                                        class="fa fa-plus"></i>
                                    Add Agent</a>
                            @endif
                            @if (Session::get('role') == 'classic' || Session::get('role') == 'subadmin' || Session::get('role') == 'executive' || Session::get('role') == 'premium' || Session::get('role') == 'Admin')
                                <a href="{{ url('/Franchise/add_player') }}" class="btn btn-success"><i
                                        class="fa fa-plus"></i>
                                    Add Player</a>
                            @endif
                        @endif
                    </div>
                    <div class="row text-right">
                        <a href="javascript:history.back()" class="btn btn-success"><i
                                class="fa fa-arrow-left mr-2"></i>Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    @if (Request::segment(2) == 'add_agent')
                        <h6>Add Agent</h6>
                    @elseif (Request::segment(2) == 'add_premium')
                        <h6>Add Premium</h6>
                    @elseif(Request::segment(2) == 'add_distributer')
                        <h6>Add Distributer</h6>
                    @elseif(Request::segment(2) == 'add_agent')
                        <h6>Add Agent</h6>
                    @elseif(Request::segment(2) == 'add_player')
                        <h6>Add Player</h6>
                    @endif
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('/admin') }}" enctype="multipart/form-data">
                        @csrf
                        @if (Session::has('msg'))
                            <div class="alert alert-danger" role="alert">{{ Session::get('msg') }}
                            </div>
                        @elseif(Session::has('success'))
                            <div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
                        @endif
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Username</label>
                            <div class="col-sm-6">
                                <input type="text"
                                    class="form-control ui-autocomplete-input @error('userName') is-invalid @enderror"
                                    id="UserName" value="{{ Old('userName') }}" name="userName" autocomplete="off"
                                    placeholder="Enter userName">
                                @error('userName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <div id="checkUser"></div>
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Name</label>
                            <div class="col-sm-6">
                                <input type="text"
                                    class="form-control ui-autocomplete-input @error('name') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ Old('name') }}" name="name" autocomplete="off"
                                    placeholder="Enter Name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if (Session::get('role') == 'Admin' || Session::get('role') == 'agent' || Session::get('role') == 'premium' || Session::get('role') == 'executive' || Session::get('role') == 'classic')
                        @if (Request::segment(1) == 'Franchise')
                                @if (Request::segment(2) == 'add_premium')
                                    <input type="hidden" name="role" value="3" id="role">
                                    {{-- <option value="3">f_Premium</option> --}}
                                @elseif(Request::segment(2) == 'add_distributer')
                                    <input type="hidden" name="role" value="5" id="role">
                                    {{-- <option value="5">f_Executive</option> --}}
                                @elseif(Request::segment(2) == 'add_agent')
                                    <input type="hidden" name="role" value="6" id="role">
                                    {{-- <option value="6">f_Classic</option> --}}
                                @elseif(Request::segment(2) == 'add_player')
                                    <input type="hidden" name="role" value="7" id="role">
                                    {{-- <option value="7">f_player</option> --}}
                                @endif
                            @else
                                @if (Request::segment(2) == 'add_agent')
                                    <input type="hidden" name="role" value="1" id="role">
                                    {{-- <option value="1">Agent</option> --}}
                                @elseif (Request::segment(2) == 'add_premium')
                                    <input type="hidden" name="role" value="3" id="role">
                                    {{-- <option value="3">Premium</option> --}}
                                @elseif(Request::segment(2) == 'add_distributer')
                                    <input type="hidden" name="role" value="5" id="role">
                                    {{-- <option value="5">Executive</option> --}}
                                @elseif(Request::segment(2) == 'add_agent')
                                    <input type="hidden" name="role" value="6" id="role">
                                    {{-- <option value="6">Classic</option> --}}
                                @elseif(Request::segment(2) == 'add_player')
                                    <input type="hidden" name="role" value="7" id="role">
                                    {{-- <option value="7">player</option> --}}
                                @endif
                            @endif
                        @endif

                        @if (Session::get('role') == 'agent' || Session::get('role') == 'premium' || Session::get('role') == 'Admin' || Session::get('role') == 'executive' || Session::get('role') == 'classic')
                            @if (Request::segment(2) == 'add_agent' || Request::segment(2) == 'add_player' || (Request::segment(2) == 'add_premium' && Request::segment(1) != 'Franchise'))
                                <div class="form-group d-flex" id="referral2">
                                    <label class="col-sm-2 offset-lg-1 text-right control-label mt-2" id="s1">Parent</label>
                                    <div class="col-sm-6" id="s2">
                                        <div class="form-group mb-2">
                                            <select class="form-control superDistributerId" name="superDistributerId"
                                                id="superDistributerId">
                                                <option selected disabled>Select Substitute</option>
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
                        @endif
                        {{-- @if (Session::get('role') == 'superDistributer' || Session::get('role') == 'Admin')
                            <div class="d-flex" id="referral">

                            </div>
                        @endif --}}
                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Password</label>
                            <div class="col-sm-6">
                                <input type="text"
                                    class="form-control ui-autocomplete-input @error('password') is-invalid @enderror"
                                    id="exampleInputPassword1" value="{{ Old('password') }}" name="password"
                                    autocomplete="off" placeholder="Enter Password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div><span class="text-danger">The password must be at least 6
                                        characters.</span>
                                </div>
                            </div>
                        </div>
                        @if (Request::segment(2) != 'add_player')
                            <div class="form-group d-flex">
                                <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Transaction
                                    Pin</label>
                                <div class="col-sm-6">
                                    <input type="number"
                                        class="form-control ui-autocomplete-input @error('transactionPin') is-invalid @enderror"
                                        id="exampleInputTransactionpin" value="{{ Old('transactionPin') }}"
                                        name="transactionPin" autocomplete="off" placeholder="Enter Transaction Pin">
                                    @error('transactionPin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div><span class="text-danger">Transaction password and password are not
                                            same!!</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                            <div class="form-group d-flex" id="comPoint">
                                <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Sharing %</label>
                                <div class="col-sm-6">
                                    <input type="text"
                                        class="form-control ui-autocomplete-input @error('commissionPercentage') is-invalid @enderror"
                                        id="exampleSharing1" value="{{ Old('commissionPercentage') }}"
                                        name="commissionPercentage" autocomplete="off" placeholder="Enter Commission %">
                                    @error('commissionPercentage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div id="commssion"></div>
                                </div>
                            </div>

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
                        
                        <input type="hidden" name="is_franchise" id="is_franchise"
                            value="{{ Request::segment(1) == 'Franchise' ? 'true' : 'false' }}" />
                        <div class="form-group d-flex" id="perissions">
                            {{-- <label class="col-sm-2 offset-lg-1 text-right control-label mt-2">Page Permission</label> --}}
                            <div class="col-sm-3">
                                {{-- <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="add_user">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="add_user">
                                        Add User
                                    </label>
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="view_user">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="view_user">
                                        View User
                                    </label>
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="superdistributer">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="superdistributer">
                                        Super Distributor
                                    </label>
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="distributer">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="distributer">
                                        Distributor
                                    </label>
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="retailer">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="retailer">
                                        Retailer
                                    </label>
                                </div> --}}
                            </div>
                            <div class="col-sm-3">
                                {{-- <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="winningPercent">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="winningPercent">
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
                                            value="winbyadmin">
                                        Win By Admin
                                    </label>
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label" id="announcement">
                                        <input type="checkbox" class="form-check-input" name="permission[]"
                                            value="announcement">
                                        Announcement
                                    </label>
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="permission[]" id="complaint"
                                            value="complaint">
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
    <script>
        window.onload = function() {
            var Role = $('#role').val();
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
                        $("#retailer").attr("checked", "checked");
                        // $('.superDistributerId').html(res);
                        $('#referral').addClass('form-group');
                        $('#superDistributerId').html(res);
                    }
                });
            }
        }
        var token = $('input[name="_token"]').val();
        $(document).ready(function() {
            $('#superDistributerId').change(function() {
                var id = $(this).val();
                var is_f = $('#is_franchise').val();
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

            $('#UserName').on('keyup', (function(e) {
                // alert(e.val());
                // console.log();
                var check = $(this).val();
                var token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ url('/checkUserName') }}",
                    type: 'POST',
                    data: {
                        check: check,
                        _token: token
                    },
                    success: function(res) {
                        $('#referral').addClass('form-group');
                        $('#checkUser').html(res);
                    }
                });
            }));
        })
    </script>
@endpush
