@extends('layout.master')
@section('content')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="card col-md-6">
                <div class="card-header">
                    <h1 class="card-title">{{ isset($market) ? 'Edit Market' : 'Create Market' }}</h1>
                </div>
                <div class="card-body">
                    <form method="POST"
                        action="{{ isset($market) ? route('markets.update', $market->id) : route('markets.store') }}">
                        @csrf
                        @if (isset($market))
                            @method('PUT')
                        @endif
                        <div class="form-group">
                            <label for="name">Market Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ isset($market) ? $market->name : old('name') }}" placeholder="Market Name">
                        </div>

                        <div class="form-group">
                            <label for="gamename">Game Name</label>
                            <input type="text" class="form-control" id="gamename" name="gamename"
                                value="{{ isset($market) ? $market->gamename : old('gamename') }}" placeholder="Game Name">
                        </div>

                        <div class="form-group">
                            <label for="market">Market</label>
                            <input type="text" class="form-control" id="market" name="market"
                                value="{{ isset($market) ? $market->market : old('market') }}" placeholder="Market">
                        </div>

                        <div class="form-group">
                            <label for="mon_start_time">Monday Start Time</label>
                            <input type="time" class="form-control" id="mon_start_time" name="mon_start_time"
                                value="{{ isset($market) ? $market->mon_start_time : old('mon_start_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="mon_close_time">Monday Close Time</label>
                            <input type="time" class="form-control" id="mon_close_time" name="mon_close_time"
                                value="{{ isset($market) ? $market->mon_close_time : old('mon_close_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="tue_start_time">Tuesday Start Time</label>
                            <input type="time" class="form-control" id="tue_start_time" name="tue_start_time"
                                value="{{ isset($market) ? $market->tue_start_time : old('tue_start_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="tue_close_time">Tuesday Close Time</label>
                            <input type="time" class="form-control" id="tue_close_time" name="tue_close_time"
                                value="{{ isset($market) ? $market->tue_close_time : old('tue_close_time') }}">
                        </div>


                        <div class="form-group">
                            <label for="wed_start_time">Wednesday Start Time</label>
                            <input type="time" class="form-control" id="wed_start_time" name="wed_start_time"
                                value="{{ isset($market) ? $market->wed_start_time : old('wed_start_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="wed_close_time">Wednesday Close Time</label>
                            <input type="time" class="form-control" id="wed_close_time" name="wed_close_time"
                                value="{{ isset($market) ? $market->wed_close_time : old('wed_close_time') }}">
                        </div>



                        <div class="form-group">
                            <label for="thu_start_time">Thursday Start Time</label>
                            <input type="time" class="form-control" id="thu_start_time" name="thu_start_time"
                                value="{{ isset($market) ? $market->thu_start_time : old('thu_start_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="thu_close_time">Thursday Close Time</label>
                            <input type="time" class="form-control" id="thu_close_time" name="thu_close_time"
                                value="{{ isset($market) ? $market->thu_close_time : old('thu_close_time') }}">
                        </div>



                        <div class="form-group">
                            <label for="fri_start_time">Friday Start Time</label>
                            <input type="time" class="form-control" id="fri_start_time" name="fri_start_time"
                                value="{{ isset($market) ? $market->fri_start_time : old('fri_start_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="fri_close_time">Friday Close Time</label>
                            <input type="time" class="form-control" id="fri_close_time" name="fri_close_time"
                                value="{{ isset($market) ? $market->fri_close_time : old('fri_close_time') }}">
                        </div>



                        <div class="form-group">
                            <label for="sat_start_time">Saturday Start Time</label>
                            <input type="time" class="form-control" id="sat_start_time" name="sat_start_time"
                                value="{{ isset($market) ? $market->sat_start_time : old('sat_start_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="sat_close_time">Saturday Close Time</label>
                            <input type="time" class="form-control" id="sat_close_time" name="sat_close_time"
                                value="{{ isset($market) ? $market->sat_close_time : old('sat_close_time') }}">
                        </div>



                        <div class="form-group">
                            <label for="sun_start_time">Sunday Start Time</label>
                            <input type="time" class="form-control" id="sun_start_time" name="sun_start_time"
                                value="{{ isset($market) ? $market->sun_start_time : old('sun_start_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="sun_close_time">Sunday Close Time</label>
                            <input type="time" class="form-control" id="sun_close_time" name="sun_close_time"
                                value="{{ isset($market) ? $market->sun_close_time : old('sun_close_time') }}">
                        </div>


                        <div class="form-group">
                            <label for="bucket">Bucket</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="bucket" id="bucket"
                                    value="{{ isset($market) ? $market->bucket : old('bucket') }}"
                                    placeholder=" Enter Percent">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="color: black"><b>%</b></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bucket3">Bucket 3</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="bucket3" id="bucket3"
                                    value="{{ isset($market) ? $market->bucket3 : old('bucket3') }}"
                                    placeholder=" Enter Percent">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="color: black"><b>%</b></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bucket5">Bucket 5</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="bucket5" id="bucket5"
                                    value="{{ isset($market) ? $market->bucket5 : old('bucket5') }}"
                                    placeholder="Enter Percent">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="color: black"><b>%</b></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="" disabled selected> Current Status
                                    :{{ isset($market) ? $market->status : old('status') }}
                                </option>
                                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
