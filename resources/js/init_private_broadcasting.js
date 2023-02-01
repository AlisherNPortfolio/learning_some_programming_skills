import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
    authEndpoint: '/pusher/auth',
    csrfToken: $('meta[name="csrf-token"]').attr('content'),
});


window.listen = (id) => {
    window.Echo.private('chat.' + id )
    .listen('.my-chat', (e) => {
        $('#chat-content').append(window.generateChatItem(e.message))

        console.log('%c' + e.message.message, 'background: blue; color: yellow;font-size:24px;')
    });
}


window.generateChatItem = (data, isSender = false) => {
    let messageItem = '<div class="media media-chat '+ (isSender ? 'media-chat-reverse' : '') +'">';
    if (!isSender) {
        messageItem += `<img class="avatar" src="https://img.icons8.com/color/36/000000/administrator-male.png" alt="...">`;
    }

    messageItem += '<div class="media-body">';
    messageItem += '<p>'+ data.message +'</p>'
    messageItem += '<p class="meta">';
    const year = (new Date()).getFullYear();
    const messageTime = typeof data.created_at == 'string' ? new Date(data.created_at): data.created_at;
    const hour = messageTime.getHours();
    const minute = messageTime.getMinutes();
    messageItem += '<time datetime="'+ year +'">'+ `${+hour >= 10 ? hour : '0'+hour}:${+minute >= 10 ? minute : '0'+minute}` +'</time>';
    messageItem += '</p></div></div>';

    return messageItem;
}
