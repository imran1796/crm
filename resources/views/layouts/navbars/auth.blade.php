<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light text-sm">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <a class="navbar-brand mx-2 p-0" href=""> {{ $navName }} </a>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{ asset('adminlte3/dist/img/user.png') }}" class="rounded-circle mr-2" alt="User Image"
                    width="32" height="32">
                <span class="d-none d-md-inline font-weight-medium">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile') }}">
                    <i class="fas fa-user mr-2 text-muted"></i> Account
                </a>
                <div class="dropdown-divider"></div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> <i class="fas fa-sign-out-alt mr-2"></i>{{ __('Sign Out') }} </a>
                </form>
                {{-- <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                    <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                </a> --}}
            </div>
        </li>
    </ul>


</nav>