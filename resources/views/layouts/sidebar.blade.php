<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #ffffff!important; color:red">
    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ session('profile_image') ? asset(session('profile_image')) : asset('assets/dist/img/default-profile.png') }}"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ session('fullName') }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- @php $role = session('admin_role_id'); @endphp -->
                @php $role = 1; @endphp

                @if(in_array($role, [1,2,3]))
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->is('allData*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-plus"></i>
                        <p>All Data<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('allData') }}" class="nav-link">
                                <i class="nav-icon fas fa-arrow-right"></i>
                                <p>Data</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('rejected') }}" class="nav-link {{ request()->is('rejected') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-times-circle"></i>
                                <p>Rejected Data</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('lead') }}" class="nav-link {{ request()->is('lead') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard"></i>
                        <p>Leads</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('telecalling') }}" class="nav-link  {{ request()->is('telecalling') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-headset"></i>
                        <p>
                            Telecalling
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Customers<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('renewal?type=customer') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Renewal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Customer</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(in_array($role, [1,2,3,4]))
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Trainers<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if($role != 3)
                        <li class="nav-item">
                            <a href="{{ route('recruiter') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Recruits</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ route('trainers') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Trainers</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if($role == 13)
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>Yoga Center<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('renewal?type=yoga') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Renewal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('yoga-bookings') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Yoga Center</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(in_array($role, [1,2,5]))
                <li class="nav-item">
                    <a href="{{ route('event') }}" class="nav-link">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>Events</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>Yoga Center<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('renewal?type=yoga') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Renewal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('yoga-bookings') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Yoga Center</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>Accounts<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('ledger') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ledger</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('summary') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Summary</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('office-expences') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Expenses</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(in_array($role, [1, 2]))
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>User<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.view') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User list</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.add') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add User</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-arrow-right"></i>
                        <p>Sign out</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>