<aside class="main-sidebar elevation-4 sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link text-center">
        <span class="brand-text font-weight-bold" style="font-size: 1rem">{{ config('app.name') }}</span>
    </a>

    <div class="sidebar">
        <!-- SidebarSearch Form -->
        <div class="form-inline mt-3">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <nav class="mt-4">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->is('home*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            {{ __('Dashboard') }}
                        </p>
                    </a>
                </li>
                @can('my book.read')
                    <li class="nav-item">
                        <a href="{{ route('user.books') }}" class="nav-link {{ request()->is('book/my-book-list*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                {{ __('My Books') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('book.read')
                    <li class="nav-item">
                        <a href="{{ route('books.index') }}" class="nav-link {{ request()->is('books*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                {{ __('Book Management') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @if(Auth::user()->can('role.read') || Auth::user()->can('user.read'))
                    <li class="nav-item {{ request()->is('roles*') || request()->is('users*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('roles*') || request()->is('users*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                {{ __('Users & Roles') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('role.read')
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}" class="nav-link {{ request()->is('roles*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Role Management') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('user.read')
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('User Management') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
