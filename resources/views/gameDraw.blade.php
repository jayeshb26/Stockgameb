@extends('layout.master')

@push('style')
    <style type="text/css">
        .panel-primary {
            border-color: #337ab7;
        }

    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card panel-primary">
                <div class="card-header bg-primary">
                    <div class="col-md-12 d-flex">
                        <span class="col-md-6 text-white font-weight-bold" style="font-size:16px;">Order Details -
                            {{ $game }}</span>
                        <div class="col-md-6 text-right">
                            {{-- <div class="dropdown pull-right">
                                <button class="btn btn-outline-success text-white dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">Draw Details</button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ url('gamedraw/1') }}">RouletteTimer60</a>
                                    <a class="dropdown-item" href="{{ url('gamedraw/2') }}">RouletteTimer40</a>
                                    <a class="dropdown-item" href="{{ url('gamedraw/3') }}">Roulette</a>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div id="accordion" class="accordion" role="tablist">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td>Sl no</td>
                                        <td>Position</td>
                                        <td>Name</td>
                                        <td>Symbol</td>
                                        <td>Time</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($data as $key => $bets)
                                    {{-- @dd($bets['result']['name']); --}}
                                        <tr>
                                            <td><?= $data->firstItem() + $key ?></td>
                                            <td>{{ $bets['result']['number'] }}</td>
                                            @if ($game == 'Andar Bahar')
                                                <td><img src="{{ asset('assets/images/card/' . $bets['result'] . '.png') }}"
                                                        style="border-radius: 0px; width: 5%;"></td>
                                            @else
                                                <td>{{ $bets['result']['name'] }}</td>
                                            @endif
                                            <td>{{ $bets['result']['symbol'] }}</td>
                                            <td>{{$bets['createDate']}} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">{{ $data->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
