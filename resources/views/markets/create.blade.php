@extends('layout.master')
@section('content')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />

    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="card col-md-6">
                <div class="card-header">
                    <h5 class="card-title">Create Market</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('markets.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">Market Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter market name" value="{{ old('name') }}">
                        </div>

                        <div class="form-group">
                            <label for="gamename">Game Name</label>
                            <input type="text" class="form-control" id="gamename" name="gamename"
                                placeholder="Enter game name" value="{{ old('gamename') }}">
                        </div>

                        <div class="form-group">
                            <label for="market">Market</label>
                            <input type="text" class="form-control" id="market" name="market"
                                placeholder="Enter market" value="{{ old('market') }}">
                        </div>

                        <div class="form-group">
                            <label for="mon_start_time">Monday Start Time</label>
                            <input type="time" class="form-control" id="mon_start_time" name="mon_start_time"
                                value="{{ old('mon_start_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="mon_close_time">Monday Close Time</label>
                            <input type="time" class="form-control" id="mon_close_time" name="mon_close_time"
                                value="{{ old('mon_close_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="tue_start_time">Tuesday Start Time</label>
                            <input type="time" class="form-control" id="tue_start_time" name="tue_start_time"
                                value="{{ old('tue_start_time') }}">
                        </div>

                        <div class="form-group">
                            <label for "tue_close_time">Tuesday Close Time</label>
                            <input type="time" class="form-control" id="tue_close_time" name="tue_close_time"
                                value="{{ old('tue_close_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="wed_start_time">Wednesday Start Time</label>
                            <input type="time" class="form-control" id="wed_start_time" name="wed_start_time"
                                value="{{ old('wed_start_time') }}">
                        </div>

                        <div class="form-group">
                            <label for "wed_close_time">Wednesday Close Time</label>
                            <input type="time" class="form-control" id="wed_close_time" name="wed_close_time"
                                value="{{ old('wed_close_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="thu_start_time">Thursday Start Time</label>
                            <input type="time" class="form-control" id="thu_start_time" name="thu_start_time"
                                value="{{ old('thu_start_time') }}">
                        </div>

                        <div class="form-group">
                            <label for "thu_close_time">Thursday Close Time</label>
                            <input type="time" class="form-control" id="thu_close_time" name="thu_close_time"
                                value="{{ old('thu_close_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="fri_start_time">Friday Start Time</label>
                            <input type="time" class="form-control" id="fri_start_time" name="fri_start_time"
                                value="{{ old('fri_start_time') }}">
                        </div>

                        <div class="form-group">
                            <label for "fri_close_time">Friday Close Time</label>
                            <input type="time" class="form-control" id="fri_close_time" name="fri_close_time"
                                value="{{ old('fri_close_time') }}">
                        </div>


                        <div class="form-group">
                            <label for="sat_start_time">Saturday Start Time</label>
                            <input type="time" class="form-control" id="sat_start_time" name="sat_start_time"
                                value="{{ old('sat_start_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="sat_close_time">Saturday Close Time</label>
                            <input type="time" class="form-control" id="sat_close_time" name="sat_close_time"
                                value="{{ old('sat_close_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="sun_start_time">Sunday Start Time</label>
                            <input type="time" class="form-control" id="sun_start_time" name="sun_start_time"
                                value="{{ old('sun_start_time') }}">
                        </div>

                        <div class="form-group">
                            <label for="sun_close_time">Sunday Close Time</label>
                            <input type="time" class="form-control" id="sun_close_time" name="sun_close_time"
                                value="{{ old('sun_close_time') }}">
                        </div>


                        <div class="form-group">
                            <label for="bucket">Bucket</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="bucket" id="bucket"
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
                                    placeholder=" Enter Percent">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="color: black"><b>%</b></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Create Market</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
