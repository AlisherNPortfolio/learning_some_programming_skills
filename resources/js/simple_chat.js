import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

$(document).ready(function (){

    $(document).on('click','#sent_message' ,function (e){

        e.preventDefault()

        let username = $('#username').val();
        let message = $('#message').val();

        if (username == '' || message == ''){
            alert('Plz, enter both username and message')
            return false;
        }

        $.ajax({
            method: "POST",
            url: "/send-message",
            data:{username:username, message:message},
            success: function (response){

            }

        });

    });
});

window.Echo.channel('chat')
.listen('.message', (e)=>{
    $('#messages').append('<p><strong>'+e.username+'</strong>'+ ':' + e.message+'</p>');
    $('#message').val('');
})
