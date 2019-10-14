<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">

        <!-- force google maps overlay in the background -->
        <google-map></google-map>

        <!-- begin navbar -->
        <nav class="navbar navbar-expand bg-primary border-primary navbar-light shadow-sm p-0">
            <div class="container align-center">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ url('/') }}">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a
                                class="nav-link text-white"
                                v-bind:class="{ frozen: this.$root.user && this.$root.user.freeze_location === 1 }"
                                @@click="this.$root.freezeLocation"
                                href="#"
                            >
                                @{{
                                    this.$root.user && this.$root.user.freeze_location === 1 ?
                                    'Unlock' :
                                    'Lock'
                                }} Location
                            </button>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>

        <!-- begin body -->
        <main class="py-4">
            @yield('content')
        </main>

        <div class="icon-legend d-none d-lg-block d-m-block shadow-sm rounded border">
            <div class="legend-me">You</div>
            <div class="legend-another">Other</div>
        </div>
    </div>
</body>
</html>
