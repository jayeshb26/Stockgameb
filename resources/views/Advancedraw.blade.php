@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.css') }}"
    rel="stylesheet" />
<link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endpush



@section('content')

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card d-flex">
            <div class="card-body">
                <form method="get" id="myFormID" action="{{ url('/history') }}">
                    <div class="forms-sample">
                        <div class="form-group row">
                            <div class="col-sm-1 text-center mt-2">Ticket ID</div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="ticket">
                            </div>
                            <div class="col-sm-2">
                                <input class="btn btn-primary" type="submit" value="Submit">
                            </div>
                            @if(Session::get('role')!="retailer")
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-outline-info" data-toggle="modal"
                                    data-target="#myModal">Advance Filter</button>
                            </div>
                            @endif
                            @if(Session::get('role')!="retailer")
                            <div class="col-sm-2">
                                <a href="{{url('/Advancedraw')}}" type="button" class="btn btn-outline-success">Advance
                                    Draw</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@if(Session::get('role')!="retailer")
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Game Summary</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <form action="{{url('/history')}}" method="get">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Username<br>
                            <select name="username" class="form-control" required>
                                <option value="">Select the Username</option>
                                @foreach($users as $value)
                                <option value="{{$value['_id']}}">{{$value['userName']}}</option>
                                @endforeach
                            </select>
                            <br>
                            Date From
                            <div class="input-group date datepicker" id="datePickerExample1">
                                <input type="text" class="form-control" name="dateFrom" placeholder="From"><span
                                    class="input-group-addon"><i data-feather="calendar"></i></span>
                            </div>
                            <br>
                            Date To
                            <div class="input-group date datepicker" id="datePickerExample">
                                <input type="text" class="form-control" name="dateTo" placeholder="To"><span
                                    class="input-group-addon"><i data-feather="calendar"></i></span>
                            </div>
                            <br>
                            Draw Time
                            <select name="DrTime" class="form-control">
                                <option value="">Select the Draw Time</option>
                                <option value="8 : 15">8:15 AM</option>
                                <option value="8 : 30">8:30 AM</option>
                                <option value="8 : 45">8:45 AM</option>
                                <option value="9 : 0">9:00 AM</option>
                                <option value="9 : 15">9:15 AM</option>
                                <option value="9 : 30">9:30 AM</option>
                                <option value="9 : 45">9:45 AM</option>
                                <option value="10 : 0">10:00 AM</option>
                                <option value="10 : 15">10:15 AM</option>
                                <option value="10 : 30">10:30 AM</option>
                                <option value="10 : 45">10:45 AM</option>
                                <option value="11 : 0">11:00 AM</option>
                                <option value="11 : 15">11:15 AM</option>
                                <option value="11 : 30">11:30 AM</option>
                                <option value="11 : 45">11:45 AM</option>
                                <option value="12 : 0">12:00 PM</option>
                                <option value="12 : 15">12:15 PM</option>
                                <option value="12 : 30">12:30 PM</option>
                                <option value="12 : 45">12:45 PM</option>
                                <option value="13 : 0">1:00 PM</option>
                                <option value="13 : 15">1:15 PM</option>
                                <option value="13 : 30">1:30 PM</option>
                                <option value="13 : 45">1:45 PM</option>
                                <option value="14 : 0">2:00 PM</option>
                                <option value="14 : 15">2:15 PM</option>
                                <option value="14 : 30">2:30 PM</option>
                                <option value="14 : 45">2:45 PM</option>
                                <option value="15 : 0">3:00 PM</option>
                                <option value="15 : 15">3:15 PM</option>
                                <option value="15 : 30">3:30 PM</option>
                                <option value="15 : 45">3:45 PM</option>
                                <option value="16 : 0">4:00 PM</option>
                                <option value="16 : 15">4:15 PM</option>
                                <option value="16 : 30">4:30 PM</option>
                                <option value="16 : 45">4:45 PM</option>
                                <option value="17 : 0">5:00 PM</option>
                                <option value="17 : 15">5:15 PM</option>
                                <option value="17 : 30">5:30 PM</option>
                                <option value="17 : 45">5:45 PM</option>
                                <option value="18 : 0">6:00 PM</option>
                                <option value="18 : 15">6:15 PM</option>
                                <option value="18 : 30">6:30 PM</option>
                                <option value="18 : 45">6:45 PM</option>
                                <option value="19 : 0">7:00 PM</option>
                                <option value="19 : 15">7:15 PM</option>
                                <option value="19 : 30">7:30 PM</option>
                                <option value="19 : 45">7:45 PM</option>
                                <option value="20 : 0">8:00 PM</option>
                                <option value="20 : 15">8:15 PM</option>
                                <option value="20 : 30">8:30 PM</option>
                                <option value="20 : 45">8:45 PM</option>
                                <option value="21 : 0">9:00 PM</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Get Summary</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Advance Draw Player History</h6>
                {{-- <p class="card-description">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> --}}
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL.No</th>
                                <th>Date</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Start Point</th>
                                <th>Bet</th>
                                <th>Won</th>
                                <th>End Point</th>
                                <th>Series No</th>
                                <th>Draw Time</th>
                                <th>Advance Draw</th>
                                <th>Claim Status</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $SR_No = 1;
                            @endphp
                            @foreach ($data as $value)
                            <tr role="row">
                                <td class="">{{$SR_No++}}</td>
                                <td>{{date("d-m-Y h:i:s A",strtotime($value['createdAt']))}}</td>
                                <td>{{$value['userName']}}<br>{{$value['ticketId']}}</td>
                                <td>{{$value['name']}}</td>
                                <td>{{number_format($value['startPoint'],2)}}</td>
                                <td>{{number_format($value['betPoint'],2)}}</td>
                                <td>{{number_format($value['won'],2)}}</td>
                                <td>{{number_format($value['startPoint']-$value['betPoint']+$value['won'],2)}}</td>
                                <td>{{$value['seriesNo']*10}}</td>
                                <td>{{str_replace(' ', '', $value['DrTime'])}}</td>
                                <td>{{ ($value['isAdvance']) ? 'Yes' : 'No' }}</td>
                                <td>{{ ($value['claim']) ? 'Yes' : 'No' }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary mx-1" data-toggle="modal"
                                        data-target="#message{{$value['_id']}}">view</button>

                                    <div id="message{{$value['_id']}}" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-xl">

                                            <!-- Modal content-->
                                            <div class="modal-content" style="max-width: 1140px;">
                                                <div class="modal-header">

                                                    <h4 class="modal-title" id="myModalLabel">Play History</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close"><span aria-hidden="true">×</span></button>
                                                </div>
                                                <div class="modal-body" id="contain">
                                                    <div class="">
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <td><strong>Id</strong></td>
                                                                    <td>{{$value['_id']}}</td>
                                                                    <td><strong>Player</strong></td>
                                                                    <td>{{$value['userName']}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Game</strong></td>
                                                                    <td>Coupon</td>
                                                                    <td><strong>Date</strong></td>
                                                                    <td><?php echo date("d/m/Y h:i A",strtotime($value['createdAt']));?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Bet</strong></td>
                                                                    <td>{{number_format($value['betPoint'],2)}}</td>
                                                                    <td><strong>Won</strong></td>
                                                                    <td>{{number_format($value['won'],2)}}</td>
                                                                </tr>
                                                                @php
                                                                $winpos = "";
                                                                foreach($value['winPositions'] as $v){
                                                                $length = 2;
                                                                $winpos .= str_pad($v,$length,"0", STR_PAD_LEFT).",";
                                                                }
                                                                $winpos = trim($winpos,',');
                                                                @endphp
                                                                <tr>
                                                                    <td><strong>Win Position</strong></td>
                                                                    <td>{{"[".$winpos."]"}}</td>
                                                                    <td><strong>Series No</strong></td>
                                                                    <td>{{$value['seriesNo']*10}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <br>
                                                        <div style="width: 100%; float: left;">
                                                            <table class="table table-bordered" style="width: 300px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td>#</td>
                                                                        <td>Bet</td>
                                                                        <td>Won</td>
                                                                        <td>Result</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Lottery</td>
                                                                        <td>{{number_format($value['betPoint'],2)}}</td>
                                                                        <td>{{number_format($value['won'],2)}}</td>
                                                                        <td>{{"[".$winpos."]"}}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <div style="clear: both; height: 10px;"></div>
                                                        <div style="clear: both; height: 10px;"></div>
                                                        @php
                                                        $ser = $value['seriesNo'];
                                                        $po=$ser*1000;
                                                        $no=$po;
                                                        $win = $value['winPositions'];
                                                        $bets = $value['ticketBets'];

                                                        // if($win != ""){
                                                        // echo "vijay";
                                                        // }
                                                        // die;
                                                        // $k = 1;
                                                        // foreach($bets as $key => $v){
                                                        // echo $key;
                                                        // if($key==chr(64+$k)){
                                                        // foreach ($v as $ke => $p){
                                                        // echo $ke."_".$p.","."<br>";
                                                        // }
                                                        // }
                                                        // $k++;
                                                        // }
                                                        // die;
                                                        // echo "
                                                        <pre>".'fvhfbvfhvbfhvbfh';
                                                                        //   $app = array("bana"=>array("1"=>2),"app","gerp");
                                                                        // echo in_array("bana",$app);
                                                                        
                                                                        // die;
                                                                    @endphp
                                                                    <h2>Bet Details</h2>
                                                                    <table class="table table-bordered">
                                                                        <tbody>
                                                                        <tr>
                                                                        <th>Bet Position</th>
                                                                        </tr>
                                                                        @for($k=1;$k<=10;$k++) 
                                                                        <tr><td class="text-center">{{chr(64+$k)}} {{$no}}-{{$no+99}}</td></tr>    
                                                                        <tr>
                                                                            <td>
                                                                            <table style="border:2px solid #4f7dda;" class="text-center">
                                                                                <tbody>
                                                                                @for($i=1;$i<=5;$i++)
                                                                                    <tr class="text-center">
                                                                                    @for($j=1000;$j<=1019;$j++)
                                                                                        @php 
                                                                                            // echo $no;
                                                                                            $check = substr($no,-2);
                                                                                            // $check1 = substr($no,-1);
                                                                                            // echo $win[$k-1];
                                                                                            // die;
                                                                                            $bull = false;
                                                                                            $length = 2;
                                                                                        @endphp
                                                                                        @if(isset($bets[chr(64+$k)]))
                                                                                            @if(isset($bets[chr(64+$k)][$check]))
                                                                                                @php  $bull = true; @endphp
                                                                                                @if(isset($win[$k-1]))
                                                                                                    @if($check==$win[$k-1] && $win[$k-1]!="")
                                                                                                        <td style="padding:0.2rem 0.2rem;line-height: 1rem;vertical-align: unset;"><span style="font-size:14px;">{{$no++}}</span><br>
                                                                                                        <span style="border:1px solid #fff;padding:2px 9px;color:#000;background:#37c22b;">{{str_pad($bets[chr(64+$k)][$check],$length,"0", STR_PAD_LEFT)}}</span></td><!--46A4FF-->
                                                                                                    @else
                                                                                                        <td style="padding:0.2rem 0.2rem;line-height: 1rem;vertical-align: unset;"><span style="font-size:14px;">{{$no++}}</span><br>
                                                                                                        <span style="border:1px solid #fff;padding:2px 9px;color:#000;background:#46A4FF;">{{str_pad($bets[chr(64+$k)][$check],$length,"0", STR_PAD_LEFT)}}</span></td><!--46A4FF-->
                                                                                                    @endif
                                                                                                @else
                                                                                                    <td style="padding:0.2rem 0.2rem;line-height: 1rem;vertical-align: unset;"><span style="font-size:14px;">{{$no++}}</span><br>
                                                                                                    <span style="border:1px solid #fff;padding:2px 9px;color:#000;background:#46A4FF;">{{str_pad($bets[chr(64+$k)][$check],$length,"0", STR_PAD_LEFT)}}</span></td><!--46A4FF-->
                                                                                                @endif
                                                                                            @endif
                                                                                        @endif
                                                                                        @if($bull==false)
                                                                                            @if(isset($win[$k-1]))
                                                                                                @if($check==$win[$k-1] && $win[$k-1]!="")
                                                                                                    <td style="padding:0.2rem 0.2rem;line-height: 1rem;vertical-align: unset;"><span style="font-size:14px;">{{$no++}}</span><br>
                                                                                                    <span style="border:1px solid #fff;padding:2px 9px;color:#000;background:#ff3366;">00</span></td><!--46A4FF-->
                                                                                                @else
                                                                                                    <td style="padding:0.2rem 0.2rem;line-height: 1rem;vertical-align: unset;"><span style="font-size:14px;">{{$no++}}</span><br>
                                                                                                    <span style="border:1px solid #fff;padding:2px 9px;color:#000;background:#D9DDFF;">00</span></td><!--46A4FF-->
                                                                                                @endif
                                                                                            @else
                                                                                                <td style="padding:0.2rem 0.2rem;line-height: 1rem;vertical-align: unset;"><span style="font-size:14px;">{{$no++}}</span><br>
                                                                                                <span style="border:1px solid #fff;padding:2px 9px;color:#000;background:#D9DDFF;">00</span></td><!--46A4FF-->
                                                                                            @endif
                                                                                            
                                                                                        @endif
                                                                                    @endfor
                                                                                    </tr>
                                                                                @endfor
                                                                                </tbody>
                                                                            </table>
                                                                            </td>
                                                                        </tr>
                                                                        @endfor
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{$data->links()}}</div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
  <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script type="text/javascript">
        $("#datePickerExample").datepicker();
        $("#datePickerExample1").datepicker();
        $("#datetimepickerExample").datetimepicker({
            format: 'LT'
        });
        $("#datetimepickerExample1").datetimepicker({
            format: 'LT'
        });
    </script>
@endpush

@push('plugin-scripts')

  <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.js') }}"></script>
@endpush

@push('custom-scripts')

  <script src="{{ asset('assets/js/timepicker.js') }}"></script>
@endpush