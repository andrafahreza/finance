<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        {{-- <div class="m-header">
            <a href="#" class="b-brand text-primary">
                <span>App</span>
                <span class="badge bg-light-success rounded-pill ms-2 theme-version">Finance</span>
            </a>
        </div> --}}
        <div class="navbar-content">
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="../assets/images/user/avatar-1.jpg" alt="user-image"
                                class="user-avtar wid-45 rounded-circle" />
                        </div>
                        <div class="flex-grow-1 ms-3 me-2">
                            <h6 class="mb-0">{{ Auth()->user()->name }}</h6>
                            <small>{{ strtoupper(str_replace('_', ' ', Auth()->user()->role)) }}</small>
                        </div>
                        <a class="btn btn-icon btn-link-secondary avtar" data-bs-toggle="collapse"
                            href="#pc_sidebar_userlink">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-sort-outline"></use>
                            </svg>
                        </a>
                    </div>
                    <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                        <div class="pt-3">
                            <a href="#!">
                                <i class="ti ti-user"></i>
                                <span>My Account</span>
                            </a>
                            <a href="{{ route('logout') }}">
                                <i class="ti ti-power"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Pages</label>
                </li>
                <li class="pc-item @if ($title == 'home') active @endif">
                    <a href="{{ route('home') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-status-up"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>
                <li class="pc-item pc-hasmenu @if ($title == 'income') active @endif">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-dollar-square"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Pemasukan</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="../dashboard/index.html">Sumber Dana</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('income') }}">Data Pemasukan</a></li>
                    </ul>
                </li>
                <li class="pc-item @if ($title == 'transaction') active @endif">
                    <a href="{{ route('transaction') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-shapes"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Transaction</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Data</label>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- [ Sidebar Menu ] end -->
