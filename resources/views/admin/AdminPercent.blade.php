@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header d-flex justify-content-between">

                    <div>
                        <h6>Admin Balance</h6><span class="text-danger">Note :- Admin Wallet Endpoint (Current Week Monday
                            to
                            Sunday)</span>
                    </div>
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
                    <div class="row mb-3">
                        <label class="col-md-1 text-right control-label mt-2"></label>
                        <div class="col-sm-6 col-md-2">
                            <label class="text-right control-label mb-0">rouletteTimer40</label>
                            <input type="number" class="form-control ui-autocomplete-input " id="series1" value=""
                                name="series1" disabled>
                        </div>
                        <div class="col-sm-6 col-md-2">
                            <label class="text-right control-label mb-0">rouletteTimer60</label>
                            <input type="number" class="form-control ui-autocomplete-input " id="series3" value=""
                                name="series3" disabled>
                        </div>
                        <div class="col-sm-6 col-md-2">
                            <label class="text-right control-label mb-0">roulette</label>
                            <input type="number" class="form-control ui-autocomplete-input " id="series5" value=""
                                name="series5" disabled>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="text-right control-label mb-0">Admin Balance Total</label>
                            <input type="number" class="form-control ui-autocomplete-input " id="total" value=""
                                name="total" disabled>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-1 text-right control-label mt-2">Admin Balance</label>
                        <div class="col-sm-6 col-md-2">
                            <input type="number" class="form-control ui-autocomplete-input " id="s1" value="0"
                                autocomplete="off" placeholder="Enter Balance">
                        </div>
                        <div class="col-sm-6 col-md-2">
                            <input type="number" class="form-control ui-autocomplete-input " id="s3" value="0"
                                autocomplete="off" placeholder="Enter Balance">
                        </div>
                        <div class="col-sm-6 col-md-2">
                            <input type="number" class="form-control ui-autocomplete-input " id="s5" value="0"
                                autocomplete="off" placeholder="Enter Balance">
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="text-right control-label mb-0">Admin Wallet Endpoint (Current Week Monday to
                                Sunday)</label>
                            <input type="number" class="form-control ui-autocomplete-input " id="totalAdmin" value=""
                                name="totalAdmin" disabled>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-1 text-right control-label mt-2"></label>
                        <div class="col-sm-6">
                            <button type="submit" id="btnSave" class="btn btn-primary">Set Admin Balance</button>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="text-right control-label mb-0">Net Profit</label>
                            <input type="number" class="form-control ui-autocomplete-input " id="NetProfit" value=""
                                name="NetProfit" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/promise-polyfill/polyfill.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
    {{-- <script src="{{ asset('js/soketAdmin.js') }}"></script> --}}
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.4.8/socket.io.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
@endpush

@push('custom-scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            startTime();

            function startTime() {
                var today = new Date();
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();
                m = checkTime(m);
                s = checkTime(s);
                $('#clock').html(h + ":" + m + ":" + s);
                setTimeout(startTime, 1000);
            }

            function checkTime(i) {
                if (i < 10) {
                    i = "0" + i
                }; // add zero in front of numbers < 10
                return i;
            }
        });
    </script>
    <script>
        $(function() {
            const socket = io.connect("ws://15.207.107.116:5000");
            socket.on("connect", function() {
                console.log(socket.id);
                const user = {
                    adminId: "603388bb7d20e50a81217277",
                };
                socket.emit("joinAdmin", user);
                socket.on("resAdmin", (res) => {
                    // console.log(res.data.rouletteTimer40.adminBalance);
                    // console.log(res.data.rouletteTimer60.adminBalance);
                    // console.log(res);
                    $("#series1").val((res.data.rouletteTimer40.adminBalance).toFixed(2));
                    $("#series3").val((res.data.rouletteTimer60.adminBalance).toFixed(2));
                    $("#series5").val(res.data.roulette.adminBalance.toFixed(2));
                    $("#total").val((res.data.rouletteTimer40.adminBalance + res.data
                            .rouletteTimer60.adminBalance + res.data.roulette.adminBalance)
                        .toFixed(2));

                    var totalAdmin = '{{ $total['EndPoint'] }}';
                    console.log(totalAdmin);
                    $('#totalAdmin').val(parseFloat(totalAdmin));
                    $('#NetProfit').val(parseFloat(totalAdmin).toFixed(2) - $("#total").val());
                });
                $("#btnSave").on("click", function() {
                    var s1 = $("#s1").val();
                    var s3 = $("#s3").val();
                    var s5 = $("#s5").val();
                    const data = {
                        rouletteTimer40: (s1 == 0) ? Math.round(parseInt($("#series1").val())) :
                            parseInt(s1),
                        rouletteTimer60: (s3 == 0) ? Math.round(parseInt($("#series3").val())) :
                            parseInt(s3),
                        roulette: (s5 == 0) ? Math.round(parseInt($("#series5").val())) :
                            parseInt(s5),
                    };
                    // console.log(data)
                    const changeAdmin = {
                        adminId: "603388bb7d20e50a81217277",
                        data,
                    };
                    // console.log(changeAdmin);
                    socket.emit("changeAdminBalance", changeAdmin);
                    $("#s1").val(0);
                    $("#s3").val(0);
                    $("#s5").val(0);
                    socket.emit("joinAdmin", user);
                    socket.on("resAdmin", (res) => {
                        // console.log(res.data.rouletteTimer40.adminBalance);
                        // console.log(res.data.rouletteTimer60.adminBalance);
                        console.log(res);
                        $("#series1").val((res.data.rouletteTimer40.adminBalance).toFixed(
                            2));
                        $("#series3").val((res.data.rouletteTimer60.adminBalance).toFixed(
                            2));
                        $("#series5").val(res.data.roulette.adminBalance.toFixed(2));
                        $("#total").val((res.data.rouletteTimer40.adminBalance + res.data
                            .rouletteTimer60.adminBalance + res.data.roulette
                            .adminBalance).toFixed(2));
                        var totalAdmin = '{{ $total['EndPoint'] }}';
                        $('#totalAdmin').val(totalAdmin);
                    });
                });
            });
        });
    </script>
@endpush
