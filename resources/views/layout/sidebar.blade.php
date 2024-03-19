<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ url('/dashboard') }}" class="sidebar-brand" style="font-size:20px">
            stock<span>Skill</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            {{-- <div class="media">
                <img class="d-flex align-self-start mr-3" src="{{ asset('img/user.png') }}" style="max-width:60px"
                    alt="Generic placeholder image">
                <div class="media-body">
                    <p class="name text-success font-weight-bold mb-0" style="font-size:15px">
                        {{ Session::get('username') }}</p>
                    <p class="email text-primary mb-0">{{ Session::get('name') }}</p>
                    @if (Session::get('role') != 'Admin')
                        @php
                            $balance = App\User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->first();
                        @endphp
                        <p class="email text-primary mb-0">{{ number_format($balance->creditPoint, 2) }}</p>
                    @endif
                </div>
            </div> --}}
            <li class="nav-item nav-category"></li>
            <li class="nav-item {{ active_class(['dashboard']) }}">
                <a href="{{ url('/dashboard') }}" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            @if (Session::get('role') == 'Admin')
                <li class="nav-item {{ active_class(['generatePointList']) }}">
                    <a href="{{ url('/generatePointList') }}" class="nav-link">
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">Generate Points</span>
                    </a>
                </li>
            @endif

            <li class="nav-item nav-category">Management</li>



            @if (Session::get('role') == 'Admin' ||
                    Session::get('role') == 'subadmin' ||
                    Session::get('role') == 'agent' ||
                    Session::get('role') == 'premium' ||
                    Session::get('role') == 'executive' ||
                    Session::get('role') == 'classic' ||
                    Session::get('role') == 'subadmin')
                @if (Session::get('is_f') == 'true')
                    <li
                        class="nav-item {{ active_class(['Franchise/add_player', 'Franchise/add_distributer', 'Franchise/add_agent']) }}">
                        @if (Session::get('role') == 'Admin' || Session::get('role') == 'subadmin')
                            {{-- <a href="{{ url('/Franchise/add_premium') }}" class="nav-link"> --}}
                            <a href="{{ url('/Franchise/add_distributer') }}" class="nav-link">
                            @elseif (Session::get('role') == 'premium')
                                <a href="{{ url('Franchise/add_distributer') }}" class="nav-link">
                                @elseif(Session::get('role') == 'executive')
                                    <a href="{{ url('Franchise/add_agent') }}" class="nav-link">
                                    @elseif(Session::get('role') == 'classic')
                                        <a href="{{ url('Franchise/add_player') }}" class="nav-link">
                        @endif
                        <i class="link-icon" data-feather="user-plus"></i>
                        <span class="link-title">Add Users</span>
                        </a>
                    </li>
                @elseif(Session::get('is_f') == 'false')
                    <li class="nav-item {{ active_class(['agents/']) }}">
                        @if (Session::get('role') == 'Admin' || Session::get('role') == 'subadmin')
                            <a href="{{ url('/agents/add_agent') }}" class="nav-link">
                            @elseif (Session::get('role') == 'agent')
                                <a href="{{ url('agents/add_premium') }}" class="nav-link">
                                @elseif (Session::get('role') == 'premium')
                                    <a href="{{ url('agents/add_executive') }}" class="nav-link">
                                    @elseif(Session::get('role') == 'executive')
                                        <a href="{{ url('agents/add_classic') }}" class="nav-link">
                                        @elseif(Session::get('role') == 'classic')
                                            <a href="{{ url('agents/add_player') }}" class="nav-link">
                        @endif
                        <i class="link-icon" data-feather="user-plus"></i>
                        <span class="link-title">Add Users</span>
                        </a>
                    </li>
                @endif
            @endif
            @if (Session::get('role') == 'Admin' ||
                    Session::get('role') == 'agent' ||
                    Session::get('role') == 'premium' ||
                    Session::get('role') == 'executive' ||
                    Session::get('role') == 'classic' ||
                    Session::get('role') == 'subadmin')
                @if (Session::get('is_f') == 'true')
                    {{-- <li class="nav-item {{ active_class(['users/Franchise']) }}">
                        <a href="{{ url('/users/Franchise') }}" class="nav-link">
                            <i class="link-icon" data-feather="user"></i>
                            <span class="link-title">View Users</span>
                        </a>
                    </li> --}}
                    <li class="nav-item {{ active_class(['users/distlist']) }}">
                        <a href="{{ url('/users/distlist') }}" class="nav-link">
                            <i class="link-icon" data-feather="user"></i>
                            <span class="link-title">View Distributers</span>
                        </a>
                    </li>
                    <li class="nav-item {{ active_class(['users/agentlist']) }}">
                        <a href="{{ url('/users/agentlist') }}" class="nav-link">
                            <i class="link-icon" data-feather="user"></i>
                            <span class="link-title">View Agents</span>
                        </a>
                    </li>
                    <li class="nav-item {{ active_class(['users/plyrlist']) }}">
                        <a href="{{ url('/users/plyrlist') }}" class="nav-link">
                            <i class="link-icon" data-feather="user"></i>
                            <span class="link-title">View Players</span>
                        </a>
                    </li>
                @elseif(Session::get('is_f') == 'false')
                    <li class="nav-item {{ active_class(['users']) }}">
                        <a href="{{ url('/users') }}" class="nav-link">
                            <i class="link-icon" data-feather="user"></i>
                            <span class="link-title">View Users</span>
                        </a>
                    </li>
                @endif
            @endif
            @if (Session::get('role') == 'Admin')
                <li class="nav-item {{ active_class(['markets']) }}">
                    <a href="{{ url('/markets') }}" class="nav-link">
                        <i class="link-icon" data-feather="activity"></i>
                        <span class="link-title">Add Markets</span>
                    </a>
                </li>
                <li class="nav-item {{ active_class(['stocks']) }}">
                    <a href="{{ url('/stocks') }}" class="nav-link">
                        <i class="link-icon" data-feather="activity"></i>
                        <span class="link-title">View Stocks List</span>
                    </a>
                </li>
                <li class="nav-item {{ active_class(['rates']) }}">
                    <a href="{{ url('/rates') }}" class="nav-link">
                        <i class="link-icon" data-feather="dollar-sign"></i>
                        <span class="link-title">View Rates List</span>
                    </a>
                </li>
            @endif
            <li class="nav-item {{ active_class(['transfer']) }}">
                <a href="{{ url('/transfer') }}" class="nav-link">
                    <i class="link-icon fa fa-exchange"></i>
                    <span class="link-title">Transfer Point</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['point_request']) }}">
                <a href="{{ url('/point_request') }}" class="nav-link">
                    <i class="link-icon fa fa-exchange"></i>
                    <span class="link-title">Point Request</span>
                </a>
            </li>

            <li class="nav-item {{ active_class(['changepin']) }}">
                <a href="{{ url('/changepin') }}" class="nav-link">
                    <i class="link-icon fa fa-key"></i>
                    <span class="link-title">Change Transaction Pin</span>
                </a>
            </li>

            <li class="nav-item nav-category">Reports</li>
            <li class="nav-item {{ active_class(['history']) }}">
                <a href="{{ url('/history') }}" class="nav-link">
                    <i class="link-icon fa fa-history"></i>
                    <span class="link-title">Players History</span>
                </a>
            </li>
            @if (Session::get('role') == 'Admin' ||
                    Session::get('role') == 'agent' ||
                    Session::get('role') == 'premium' ||
                    Session::get('role') == 'executive' ||
                    Session::get('role') == 'classic')
                <li class="nav-item {{ active_class(['gamedraw/1']) }}">
                    <a href="{{ url('/gamedraw/1') }}" class="nav-link">
                        <i class="link-icon fa fa-history"></i>
                        <span class="link-title">Draw List</span>
                    </a>
                </li>
            @endif
            @if (Session::get('role') == 'Admin' ||
                    Session::get('role') == 'agent' ||
                    Session::get('role') == 'premium' ||
                    Session::get('role') == 'executive' ||
                    Session::get('role') == 'classic')
                @if (Session::get('is_f') == 'false')
                    <li class="nav-item ">
                        @if (Session::get('role') == 'Admin')
                            <a href="{{ url('/Tnover?role=agent&type=7&from=' . date('Y-m-d') . '&to=' . date('Y-m-d')) }}"
                                class="nav-link">
                            @else
                                <a href="{{ url('/Tnover?role=' . Session::get('role') . '&type=7&from=' . date('Y-m-d') . '&to=' . date('Y-m-d')) }}"
                                    class="nav-link">
                        @endif
                        <i class="link-icon fa fa-pie-chart"></i>
                        <span class="link-title">Turnover Agent Report</span>
                        </a>
                    </li>
                @elseif(Session::get('is_f') == 'true')
                    <li class="nav-item ">
                        @if (Session::get('role') == 'Admin')
                            <a href="{{ url('/Tnover?role=franchise&type=7&from=' . date('Y-m-d') . '&to=' . date('Y-m-d')) }}"
                                class="nav-link">
                            @else
                                <a href="{{ url('/Tnover?role=' . Session::get('role') . '&type=7&from=' . date('Y-m-d') . '&to=' . date('Y-m-d')) }}"
                                    class="nav-link">
                        @endif
                        <i class="link-icon fa fa-pie-chart"></i>
                        <span class="link-title">Turnover Report</span>
                        </a>
                    </li>
                @endif
                @if (Session::get('role') == 'Admin' || Session::get('role') == 'super_distributor' || Session::get('role') == 'distributor')
                <li class="nav-item {{ active_class(['playerReport']) }}">
                    <a href="{{ url('/playerReport') }}" class="nav-link">
                        <i class="link-icon fa fa-pie-chart"></i>
                        <span class="link-title">Players Report</span>
                    </a>
                </li>
                <li class="nav-item {{ active_class(['playerwReport']) }}">
                    <a href="{{ url('/playerwReport') }}" class="nav-link">
                        <i class="link-icon fa fa-pie-chart"></i>
                        <span class="link-title">Players Weekly Report</span>
                    </a>
                </li>
                <li class="nav-item {{ active_class(['commission']) }}">
                    <a href="{{ url('/commission') }}" class="nav-link">
                        <i class="link-icon fa fa-money"></i>
                        <span class="link-title">Commission Report</span>
                    </a>
                </li>
                @endif
                @if (Session::get('role') == 'Admin')
                    <li class="nav-item {{ active_class(['gameProfit']) }}">
                        <a href="{{ url('/gameProfit?type=7&from=' . date('Y-m-d') . '&to=' . date('Y-m-d')) }}"
                            class="nav-link">
                            <i class="link-icon fa fa-history"></i>
                            <span class="link-title">Game Profit</span>
                        </a>
                    </li>
                @endif
            @endif
            {{-- <li class="nav-item {{ active_class(['transactions']) }}">
                <a href="{{ url('/transactions') }}" class="nav-link">
                    <i class="link-icon fa fa-money"></i>
                    <span class="link-title">Transaction Report</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['PointFile']) }}">
                <a href="{{ url('/PointFile') }}" class="nav-link">
                    <i class="link-icon fa fa-money"></i>
                    <span class="link-title">Point File</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['verify_pointFile']) }}">
                <a href="{{ url('/verify_pointFile') }}" class="nav-link">
                    <i class="link-icon fa fa-money"></i>
                    <span class="link-title">Verify Point File</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['points_in']) }}">
                <a href="{{ url('/points_in') }}" class="nav-link">
                    <i class="link-icon fa fa-money"></i>
                    <span class="link-title">In Points</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['points_out']) }}">
                <a href="{{ url('/points_out') }}" class="nav-link">
                    <i class="link-icon fa fa-money"></i>
                    <span class="link-title">Out Points</span>
                </a>
            </li> --}}
            {{-- @if (Session::get('role') == 'Admin' || Session::get('role') == 'agent' || Session::get('role') == 'premium' || Session::get('role') == 'executive' || Session::get('role') == 'classic')
                <li class="nav-item {{ active_class(['cmbreport']) }}">
                    <a href="{{ url('/cmbreport') }}" class="nav-link">
                        <i class="link-icon fa fa-money"></i>
                        <span class="link-title">Commission Payout Report</span>
                    </a>
                </li>
            @endif --}}
            @if (Session::get('role') == 'Admin')
                @if (array_key_exists('winningPercent', Session::get('permissions')))
                    @if (Session::get('role') == 'Admin')
                        {{-- <li class="nav-item {{ active_class(['winningPercent']) }}">
                            <a href="{{ url('/winningPercent') }}" class="nav-link">
                                <i class="link-icon fa fa-trophy"></i>
                                <span class="link-title">Winning %</span>
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['adminPercent']) }}">
                            <a href="{{ url('/adminPercent') }}" class="nav-link">
                                <i class="link-icon fa fa-money"></i>
                                <span class="link-title">Admin Balance</span>
                            </a>
                        </li> --}}
                    @endif
                @endif
                @if (array_key_exists('winbyadmin', Session::get('permissions')))
                    {{-- <li class="nav-item {{ active_class(['announcement']) }}">
                        <a href="{{ url('/announcement') }}" class="nav-link">
                            <i class="link-icon fa fa-stop"></i>
                            <span class="link-title">Announcement</span>
                        </a>
                    </li> --}}
                @endif
            @endif
        </ul>
    </div>
</nav>
