@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
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
                <div class="card-header d-flex justify-content-between">
                    <h6>Winning Percentage</h6>
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
                    <form method="post" action="{{ url('/percent') }}">
                        @csrf
                        <div class="form-group d-flex">
                            <label class="col-sm-3 text-right control-label mt-2">RouletteTimer40 Winning
                                Percentage</label>
                            <div class="col-sm-6">
                                <input type="number"
                                    class="form-control ui-autocomplete-input @error('RouletteTimer40') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ $data['rouletteTimer40'] }}"
                                    name="RouletteTimer40" autocomplete="off"
                                    placeholder="Enter RouletteTimer40 Winning Percentage">
                                @error('RouletteTimer40')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-3 text-right control-label mt-2">RouletteTimer60 Winning
                                Percentage</label>
                            <div class="col-sm-6">
                                <input type="number"
                                    class="form-control ui-autocomplete-input @error('RouletteTimer60') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ $data['rouletteTimer60'] }}"
                                    name="RouletteTimer60" autocomplete="off"
                                    placeholder="Enter RouletteTimer60 Winning Percentage">
                                @error('RouletteTimer60')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <label class="col-sm-3 text-right control-label mt-2">Roulette Winning
                                Percentage</label>
                            <div class="col-sm-6">
                                <input type="number"
                                    class="form-control ui-autocomplete-input @error('Roulette') is-invalid @enderror"
                                    id="exampleInputUsername1" value="{{ $data['roulette'] }}" name="Roulette"
                                    autocomplete="off" placeholder="Enter Roulette Winning Percentage">
                                @error('Roulette')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group d-flex">
                            <label class="col-sm-3 text-right control-label mt-2">Is Manual</label>
                            <div class="col-sm-6">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status" value="true"
                                            {{ $data['isManual'] == 1 ? 'checked' : '' }}>true
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status" value="false"
                                            {{ $data['isManual'] == 0 ? 'checked' : '' }}>False
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="isManual">
                            <div class="form-group d-flex">
                                <label class="col-sm-3 text-right control-label mt-2">0</label>
                                <div class="col-sm-6">
                                    <input type="number"
                                        class="form-control ui-autocomplete-input @error('RouletteTimer60') is-invalid @enderror"
                                        id="exampleInputUsername1" value="{{ $data['listArray'][0] }}"
                                        name="listArray[]">
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label class="col-sm-3 text-right control-label mt-2">1</label>
                                <div class="col-sm-6">
                                    <input type="number"
                                        class="form-control ui-autocomplete-input @error('RouletteTimer60') is-invalid @enderror"
                                        id="exampleInputUsername1" value="{{ $data['listArray'][1] }}"
                                        name="listArray[]">
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label class="col-sm-3 text-right control-label mt-2">2</label>
                                <div class="col-sm-6">
                                    <input type="number"
                                        class="form-control ui-autocomplete-input @error('RouletteTimer60') is-invalid @enderror"
                                        id="exampleInputUsername1" value="{{ $data['listArray'][2] }}"
                                        name="listArray[]">
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label class="col-sm-3 text-right control-label mt-2">3</label>
                                <div class="col-sm-6">
                                    <input type="number"
                                        class="form-control ui-autocomplete-input @error('RouletteTimer60') is-invalid @enderror"
                                        id="exampleInputUsername1" value="{{ $data['listArray'][3] }}"
                                        name="listArray[]">
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label class="col-sm-3 text-right control-label mt-2">4</label>
                                <div class="col-sm-6">
                                    <input type="number"
                                        class="form-control ui-autocomplete-input @error('RouletteTimer60') is-invalid @enderror"
                                        id="exampleInputUsername1" value="{{ $data['listArray'][4] }}"
                                        name="listArray[]">
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label class="col-sm-3 text-right control-label mt-2">5</label>
                                <div class="col-sm-6">
                                    <input type="number"
                                        class="form-control ui-autocomplete-input @error('RouletteTimer60') is-invalid @enderror"
                                        id="exampleInputUsername1" value="{{ $data['listArray'][5] }}"
                                        name="listArray[]">
                                </div>
                            </div>
                        </div>



                        <div class="form-group d-flex">
                            <label class="col-sm-2 offset-lg-1 text-right control-label mt-2"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Set Percentage</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/promise-polyfill/polyfill.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var isCheck = '{{ $data['isManual'] }}';
            console.log(isCheck);
            if (isCheck == 1) {
                $('#isManual').show();
            } else {
                $('#isManual').hide();
            }

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


        $("input[name='status']").change(function() {
            console.log($(this).val());
            if ($(this).val() == 'true') {
                $('#isManual').show();
            } else {
                $('#isManual').hide();
            }
        });
    </script>
@endpush
