<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Test</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    @vite(['resources/js/init_private_broadcasting.js'])
</head>

<body>
    <div class="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @if (Auth::guest())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();document.getElementById('logout-form').submit()"
                                            class="dropdown-item">Logout</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none">
                                            @csrf
                                        </form>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="m-b-md">
                <div class="row">
                    <div class="chat-header col-12 mb-3">
                        <p> You: <span class="badge bg-secondary">{{ auth()->user()->name }}</span> </p>
                        <select id="usersList" class="form-select">
                            <option>Select chat</option>
                            @foreach ($users as $user)
                                @if ($user->id != $user_id)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="chat-body col-12">
                        <div id="messages"></div>
                        <textarea class="form-control mb-3" id="chatInput" cols="30" rows="10"></textarea>
                        <button type="button" class="btn btn-primary" id="sendBtn">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>

    <script>
        const userID = '{{ $user_id ? $user_id : null }}';

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            let selectedUser = null;

            $(document).on('change', '#usersList', function(e) {
                selectedUser = +e.target.value;
            });

            $(document).on('click', '#sendBtn', function(e) {

                e.preventDefault()
                let message = $('#chatInput').val();

                if (message == '' || selectedUser === null) {
                    alert('Plz, enter both chat and message')
                    return false;
                }

                $.ajax({
                    method: "POST",
                    url: "/send-private",
                    data: {
                        userId: userID,
                        message: message,
                        whom: selectedUser
                    },
                    success: function(response) {
                        console.log(response)
                    }

                });

            });
            initEcho('{{ csrf_token() }}')

            listenChannel(`user.${userID}`)
        });




        // window.initEcho('{{ csrf_token() }}')

        // window.initChannel(userID);
    </script>
    <script defer>

    </script>

</body>

</html>
