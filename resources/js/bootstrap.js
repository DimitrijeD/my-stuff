// import _ from 'lodash';
// window._ = _;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

window.axios = axios.create({
    baseURL: "http://localhost/api/"
});

window.axios.interceptors.request.use(function (config) {
    config.headers.Authorization =  `Bearer ${localStorage.getItem("token")}`;

    return config;
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster:  import.meta.env.VITE_PUSHER_BROADCASTER,
    key:          import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost:       import.meta.env.VITE_PUSHER_WS_HOST,
    wsPort:       import.meta.env.VITE_PUSHER_WS_PORT,
    forceTLS:     import.meta.env.VITE_PUSHER_FORCE_TLS == 'true' ? true : false,
    encrypted:    import.meta.env.VITE_PUSHER_ENCRYPTED == 'true' ? true : false,
    disableStats: import.meta.env.VITE_PUSHER_DISABLE_STATS == 'true' ? true : false,
    enabledTransports: ['ws', 'wss'],
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                axios.post('broadcasting/auth', {
                    socket_id: socketId,
                    channel_name: channel.name
                },{
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem("token")}`,
                    }
                })
                .then(response => {
                    callback(false, response.data);
                })
                .catch(error => {
                    callback(true, error);
                });
            }
        };
    },
});
