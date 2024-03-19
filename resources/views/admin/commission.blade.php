@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
    @if (Session::has('msg'))
        <div class="alert alert-danger" role="alert">
            {{ Session::has('msg') ? Session::get('msg') : '' }}
        </div>
    @elseif(Session::has('success'))
        <div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
    @endif
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header d-flex justify-content-between mb-2">
                    <b>Commission Report</b>
                    {{--  <a href={{ route('CommissionPayoutReport') }} class="btn btn-primary btn-md">view Commission Payout
                        Report</a>  --}}
                </div>
                <div class="card-body">

                    <div class="form-group d-flex">
                        <div class="mr-2">
                            <label><strong>Select Role :</strong></label>
                            <div class="d-flex">
                                <select id='game_type' class="js-example-basic-single w-100" style="width: 200px">
                                    <option value="">--Select Role--</option>
                                    <option value="super_distributor">SuperDistributer</option>
                                    <option value="distributor">Distributer</option>
                                    {{--  <option value="retailer">Player</option>  --}}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>UserName</th>
                                    <th>Role</th>
                                    <th>commission_percentage</th>
                                    <th>Total Commission</th>
                                    <th>CreatedAt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user as $key => $userData)
                                    <tr class="user-row" data-role="{{ $userData['role'] }}">
                                        <th>{{ $key + 1 }}</th>
                                        <th>{{ $userData['name'] }}</th>
                                        @if ($userData['role'] == 'retailer')
                                            <th>Player</th>
                                        @elseif($userData['role'] == 'super_distributor')
                                            <th>Super Distributor</th>
                                        @elseif($userData['role'] == 'distributor')
                                            <th>Distributor</th>
                                        @endif
                                        {{--  <th>{{ $userData['role'] }}</th>  --}}
                                        <th>{{ $userData['commissionPercentage'] }}</th>
                                        <th>{{ $userData['commissionPoint'] }}</th>
                                        {{-- <th>{{ $userData['createdAt'] }}</th> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    {{--  <script type="text/javascript">
        $(function() {
            var table = $('.data-table').DataTable({
                pagination: true,

            });
        });
    </script>  --}}
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('.data-table').DataTable({
                pagination: true,
            });
            var selectedRole = ""; // Set the initial value to an empty string

            // Function to handle role selection
            function handleRoleSelection() {
                if (selectedRole === "") {
                    // If no role is selected, show all rows
                    $('.user-row').show();
                } else {
                    // Hide all rows
                    $('.user-row').hide();

                    // Show rows with the selected role
                    $('.user-row[data-role="' + selectedRole + '"]').show();
                }

                if ($.fn.DataTable.isDataTable('.data-table')) {
                    // Set the page length to -1 to display all rows
                    table.page.len(-1).draw();
                }
            }

            // Handle role selection on page load
            handleRoleSelection();

            // Handle role selection on change
            $('#game_type').on('change', function() {
                selectedRole = $(this).val();
                handleRoleSelection();
            });
        });
    </script>
@endpush
