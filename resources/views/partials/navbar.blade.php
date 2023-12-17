<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm d-flex">
    <div class="container d-flex align-items-center">
        <a href="{{ url('/home') }}" class="logo">
            <img src="{{ URL::asset('/assets/logo.svg') }}" alt="profile Pic" height="50" width="150" />
        </a>

        <div class="d-flex container align-items-center" id="navbarSupportedContent">
            <div class="container text-center" id="central">
                <ul class="navbar-nav d-flex justify-content-center gap-4">
                    @if(Auth::user() && Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.reports') }}">Reports</a>
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
                    <a class="nav-link" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" onClick="getNotifications()" aria-controls="offcanvasExample">

                        @if( Auth::user()->notifications()->where('read', false)->count() == 0 )
                        <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 448 512" style="fill: rgba(0, 0, 0, 0.65);">
                            <path d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z" />
                        </svg>
                        @else
                        <svg viewBox="152.851 226.678 33.1586 37.899" height="1.5em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M 169.43 226.678 C 168.12 226.678 167.062 227.736 167.062 229.047 L 167.062 230.371 C 161.695 231.223 157.587 235.871 157.587 241.482 L 157.587 243.954 C 157.587 247.315 156.439 250.58 154.345 253.2 L 153.242 254.584 C 152.812 255.117 152.731 255.85 153.027 256.464 C 153.323 257.078 153.945 257.471 154.626 257.471 L 184.235 257.471 C 184.916 257.471 185.537 257.078 185.833 256.464 C 186.13 255.85 186.048 255.117 185.619 254.584 L 184.516 253.207 C 182.421 250.58 181.274 247.315 181.274 243.954 L 181.274 241.482 C 181.274 235.871 177.166 231.223 171.799 230.371 L 171.799 229.047 C 171.799 227.736 170.741 226.678 169.43 226.678 Z M 169.43 233.784 L 170.023 233.784 C 174.271 233.784 177.72 237.234 177.72 241.482 L 177.72 243.954 C 177.72 247.5 178.75 250.957 180.66 253.918 L 158.202 253.918 C 160.111 250.957 161.14 247.5 161.14 243.954 L 161.14 241.482 C 161.14 237.234 164.589 233.784 168.838 233.784 L 169.43 233.784 Z M 174.168 259.839 L 169.43 259.839 L 164.693 259.839 C 164.693 261.098 165.189 262.304 166.077 263.192 C 166.965 264.081 168.172 264.577 169.43 264.577 C 170.689 264.577 171.895 264.081 172.784 263.192 C 173.672 262.304 174.168 261.098 174.168 259.839 Z" style="fill: rgba(0, 0, 0, 0.647);" />
                            <ellipse style="fill: rgb(250, 0, 0); fill-rule: nonzero; paint-order: fill markers; stroke: rgb(255, 255, 255); stroke-opacity: 0.33;" cx="178.061" cy="235.346" rx="6.42" ry="6.47" />
                        </svg>
                        @endif
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        @if(Auth::user()->provider == 'google')
                        <img class="rounded-circle shadow-1-strong me-2" src="{{ Auth::user()->image_url ? Auth::user()->image_url : 'https://static-00.iconduck.com/assets.00/user-icon-2048x2048-ihoxz4vq.png' }}" alt="avatar" width="50" height="50" />
                        @else
                        <img class="rounded-circle shadow-1-strong me-2" src="{{ Auth::user()->image_url ? asset('storage/' . Auth::user()->image_url) : 'https://static-00.iconduck.com/assets.00/user-icon-2048x2048-ihoxz4vq.png' }}" alt="avatar" width="50" height="50" />
                        @endif
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        @auth
                        <a class="dropdown-item" href="{{ route('my_events.index') }}">My events</a>
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Notifications</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div id="notificacoesContainer">
            <!-- Notifications generate by JS -->
        </div>
    </div>
</div>

<script src="{{ asset('js/notifications/notifications.js') }}"></script>

<script>
    var routeEventsShow = "{{ route('events.show', ':id') }}";

</script>

<script>
    var assetUrl = '{{ asset('
    storage / ') }}';

</script>
