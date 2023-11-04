<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm d-flex">
    <div class="container d-flex align-items-center">
        <a href="{{ url('/home') }}" class="logo"> <img src="{{ URL::asset('/assets/logo.svg') }}" alt="profile Pic" height="50" width="150" /></a>

        <div class="d-flex container align-items-center" id="navbarSupportedContent">
            <div class="container text-center" id="central">
                <ul class="navbar-nav d-flex justify-content-center gap-4">
                    @if(Auth::user() && Auth::user()->isAdmin())
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                    @endif
                    <li class="nav-item"><a class="nav-link" href="/home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/events">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">About us</a></li>
                    <li class="nav-item"><a class="nav-link" href="/help">Help/FAQ</a></li>
                </ul>
            </div>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><button class="btn btn-outline-dark">{{ __('Login') }}</button></a>
                        </li>
                    @endif
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"><button class="btn btn-primary">{{ __('Register') }}</button></a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item">My events</a>
                            @auth
                                <a class="dropdown-item" href="{{ route('profile.show', ['profile' => Auth::user()->id]) }}">Profile</a>
                            @endauth
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
