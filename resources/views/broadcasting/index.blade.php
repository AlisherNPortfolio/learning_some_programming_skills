<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Test</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand123" href="{{ url('/') }}">
                        Test
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        @if (Auth::guest())
                        <li>
                            <a href="{{ route('login') }}">Login</a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}">Register</a>
                        </li>
                        @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content">
            <div class="m-b-md">
                Yangi notification!
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="{{ asset('js/echo.js') }}"></script> --}}
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <script>
        window.onload = (e) => {
            Pusher.logToConsole = true;

            // window.Echo = new Echo({
            //     broadcaster: 'pusher',
            //     key: '84cd3fd6046bf794217b',
            //     cluster: 'ap2',
            //     encrypted: true,
            //     logToConsole: true
            // });

            Echo.private('user.{{ $user_id }}')
            .listen('NewMessageNotification', (e) => {
                alert(e.message.message);
            })
        }
    </script>

</body>
</html>
