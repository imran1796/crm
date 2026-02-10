<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-lightblue elevation-2">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        {{-- <img src="dist/img/AdminLTELogo.png" alt="Globelink" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
        <span class="brand-text font-weight-light py-3">Globelink</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar mt-2">


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link @if ($activePage == 'dashboard') active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i> {{-- Dashboard icon --}}
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- General Sidebar --}}
                <li
                    class="nav-item {{ in_array($activePage, ['user', 'configuration', 'permission', 'role', 'department', 'designation', 'branch']) ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ in_array($activePage, ['user', 'configuration', 'permission', 'role', 'department', 'designation', 'branch']) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i> {{-- General settings icon --}}
                        <p>
                            General
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('configurations.index') }}"
                                class="nav-link @if ($activePage == 'configuration') active @endif">
                                <i class="nav-icon fas fa-sliders-h"></i> {{-- Configuration sliders icon --}}
                                <p>Configuration</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}"
                                class="nav-link @if ($activePage == 'user') active @endif">
                                <i class="nav-icon fas fa-users"></i> {{-- Users icon --}}
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}"
                                class="nav-link @if ($activePage == 'role') active @endif">
                                <i class="nav-icon fas fa-user-shield"></i> {{-- Roles (shield) icon --}}
                                <p>Roles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}"
                                class="nav-link @if ($activePage == 'permission') active @endif">
                                <i class="nav-icon fas fa-key"></i> {{-- Permissions key icon --}}
                                <p>Permissions</p>
                            </a>
                        </li>

                    </ul>
                </li>
                {{-- End General Sidebar --}}

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
