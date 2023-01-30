<!doctype html>
<html lang="en">
<head>
    <title>Laravel Ajax</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.all.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.all.min.js"></script>

    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <!--Button icon-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
    @vite(['resources/css/app.css ', 'resources/js/app.js'])

</head>
<body>
<div style="padding: 30px;">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 offset-sm-3 py-2">
                <input type="text" class="form-control" id="username" name="username"
                       placeholder="Enter a user................" />
            </div>

            <div class="col-sm-6 offset-sm-3">
                <div class="box box-primary  direct-chat direct-chat-primary">
                    <div class="box-body">
                        <div class="direct-chat-messages" id="messages"></div>
                    </div>

                    <div class="box-footer">
                        <form action="" method="post" id="message_form">
                            <div class="input-group">
                                <input type="text" id="message" class="form-control" name="message" placeholder="Type a message..." />
                                <span>
                                    <button type="submit" id="sent_message" class="btn btn-primary btn-flat"><i
                                        class="fa fa-paper-plane"></i></button>
                                </span>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!--JS-->
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })


</script>

</body>
</html>
