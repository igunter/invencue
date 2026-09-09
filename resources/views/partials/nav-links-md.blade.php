<ul class="navbar-nav ms-auto d-none d-md-flex align-items-md-center">
    @auth
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2 me-1"></i>Dashboard
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userMenuMd" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuMd">
                <li>
                    <a class="dropdown-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                        <i class="bi bi-gear me-1"></i>My account
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-1"></i>Log out
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    @else
        <li class="nav-item me-2">
            <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">
                <i class="bi bi-box-arrow-in-right me-1"></i>Log in
            </a>
        </li>
        @if (Route::has('register'))
            <li class="nav-item ms-lg-2">
                <a href="{{ route('register') }}" class="btn btn-main btn-sm {{ request()->routeIs('register') ? 'active' : '' }}">
                    <i class="bi bi-person-plus me-1"></i>Register
                </a>
            </li>
        @endif
    @endauth
</ul>
