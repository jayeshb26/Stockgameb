<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
        {{-- <form class="search-form">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text">
              <i data-feather="search"></i>
            </div>
          </div>
          <input type="text" class="form-control" id="navbarForm" placeholder="Search here...">
        </div>
      </form> --}}
        <ul class="navbar-nav">
            <li class="nav-item dropdown nav-notifications">
                <label class="m-2" style="color: white" id="hidden_balance_lbl">Balance: xxxxx</label>
                <a class="nav-link dropdown-toggle m-2" href="javascript:void(0);" id="hidden_balance_a">
                    <i class="fa fa-eye-slash mr-1" style="margin-left: -13px;"></i>
                </a>

                <label class="m-2" style="color: white" id="shown_balance_lbl">Balance:
                    {{ moneyFormatIndia(Session::get('creditPoint')) }}</label>
                <a class="nav-link dropdown-toggle m-2" href="javascript:void(0);" id="shown_balance_a">
                    <i class="fa fa-eye mr-1" style="margin-left: -13px;"></i>
                </a>
            </li>
            <li class="nav-item dropdown nav-notifications">
                <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-bell">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    {{-- @if (count()) --}}
                    {{-- <div class="indicator">
                  <div class="circle"></div>
                  </div> --}}
                    {{-- @endif --}}
                </a>
            </li>
            <li class="nav-item dropdown nav-notifications">
                <a class="nav-link dropdown-toggle m-2" href="{{ url('/blockedPlayers') }}" id="notificationDropdown">
                    <i class="fa fa-users mr-1"></i>Blocked Players
                </a>
            </li>
            <li class="nav-item dropdown nav-notifications">
                <a class="nav-link dropdown-toggle m-2" href="{{ url('/chpass') }}" id="notificationDropdown">
                    <i class="fa fa-key mr-1"></i>Change Password
                </a>
            </li>
            {{-- <li class="nav-item dropdown nav-notifications">
              <a class="nav-link dropdown-toggle m-2" href="{{ url('/logout') }}" id="notificationDropdown">
                  <i class="fa fa-sign-out mr-1"></i>Logout
              </a>
          </li> --}}
            {{-- <li class="nav-item dropdown nav-notifications">
          <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              Dropdown button
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </div>
          </li> --}}
            <li class="nav-item dropdown nav-profile">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('img/user.png') }}" alt="profile">
                </a>
                <div class="dropdown-menu" aria-labelledby="profileDropdown">
                    <div class="dropdown-header d-flex flex-column align-items-center">
                        {{-- <div class="figure mb-3">
                  <img src="{{ asset('img/user.png') }}" alt="profile">
                </div> --}}
                        <div class="info text-center">
                            <p class="name font-weight-bold mb-0">{{ Session::get('name') }}</p>
                            <p class="email text-muted mb-3">{{ Session::get('username') }}</p>
                        </div>
                    </div>
                    <div class="dropdown-body">
                        <ul class="profile-nav p-0 pt-3">
                            {{-- <li class="nav-item">
                    <a href="{{ url('/general/profile') }}" class="nav-link">
                      <i data-feather="user"></i>
                      <span>Profile</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="javascript:;" class="nav-link">
                      <i data-feather="edit"></i>
                      <span>Edit Profile</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="javascript:;" class="nav-link">
                      <i data-feather="repeat"></i>
                      <span>Switch User</span>
                    </a>
                  </li> --}}
                            <li class="nav-item">
                                <a href="{{ url('/logout') }}" class="nav-link">
                                    <i data-feather="log-out"></i>
                                    <span>Log Out</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>
