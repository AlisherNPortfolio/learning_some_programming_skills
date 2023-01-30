import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

Pusher.logToConsole = true;

window.Pusher = Pusher;

window.initEcho = (token) => {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
        encrypted: true,
        logToConsole: true,
        csrfToken: token,
        authEndpoint: '/pusher/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': token,

                // API da
                // "Authorization": "Bearer YOUR_JWT_TOKEN",
                // "Access-Control-Allow-Origin": "*"
            }
        }
    });
}

window.listenChannel = (userID) => {
    window.Echo.private(`user.${userID}`)
    .listen('NewMessageNotification', (e) => {
        console.log('chat', e);
        // $('#messages').append('<p><strong>'+e.name+'</strong>'+ ':' + e.message+'</p>');
        // $('#message').val('');
    });
}
