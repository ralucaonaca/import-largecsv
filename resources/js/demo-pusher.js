
window.Pusher = require('pusher-js');

// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

import Echo from "laravel-echo"

window.echo1 = new Echo({
    broadcaster: 'pusher',
    key: '5e664e1775492ed0016c',
    cluster: 'mt1'
});

echo1.channel('demo-channel')
    .listen('CommentPostedAction', function (event) {
        console.log(event);
    })
    .listen('Illuminate\\Auth\\Events\\Login', function (event) {
        console.log(event);
    })
    .listen('Illuminate\\Auth\\Events\\Logout', function (event) {
        console.log(event);
    });