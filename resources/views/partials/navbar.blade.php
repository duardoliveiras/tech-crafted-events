
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm d-flex">
    <div class="container d-flex align-items-center">
        <a href="{{ url('/home') }}" class="logo">
            <img src="{{ URL::asset('/assets/logo.svg') }}" alt="profile Pic" height="50" width="150"/>
        </a>

        <div class="d-flex container align-items-center" id="navbarSupportedContent">
            <div class="container text-center" id="central">
                <ul class="navbar-nav d-flex justify-content-center gap-4">
                    @if(Auth::user() && Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                        </li>
                    @endif
                    <li class="nav-item"><a class="nav-link" href="{{ url('/home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">About us</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/help') }}">Help/FAQ</a></li>
                </ul>
            </div>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <button class="btn btn-outline-dark">{{ __('Login') }}</button>
                            </a>
                        </li>
                    @endif
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <button class="btn btn-primary">{{ __('Register') }}</button>
                            </a>
                        </li>
                    @endif
                @else
                    <!-- Notifications -->
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" onClick="getNotifications()"
                           aria-controls="offcanvasExample">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 448 512"
                                 style="fill: rgba(0, 0, 0, 0.65);">
                                <path d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z"/>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <img class="rounded-circle shadow-1-strong me-2"
                                 src="{{ Auth::user()->image_url ? asset('storage/' . Auth::user()->image_url) : 'https://static-00.iconduck.com/assets.00/user-icon-2048x2048-ihoxz4vq.png' }}"
                                 alt="avatar" width="50" height="50"/>
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            @auth
                                <a class="dropdown-item" href="{{ route('my_events.index') }}">My events</a>
                                <a class="dropdown-item" href="{{ route('notifications.index') }}">Notifications</a>
                                <a class="dropdown-item" href="{{ route('profile.show', ['profile' => Auth::user()->id]) }}">Profile</a>
                            @endauth
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Notifications</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul id="notificacoesLista">
            aqui vão as notificações
        </ul>
    </div>
</div>

<script src="{{ asset('js/notifications/notifications.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>