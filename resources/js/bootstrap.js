// import _ from 'lodash';
// window._ = _;


import axios from 'axios'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.withCredentials = true

window.axios = axios.create({
    baseURL: import.meta.env.VITE_APP_URL + '/api/'
})

window.axios.interceptors.request.use(function (config) {
    config.headers.Authorization = `Bearer ${localStorage.getItem("token")}`

    return config
})


import Echo from 'laravel-echo'

import Pusher from 'pusher-js'
window.Pusher = Pusher

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
                })
            }
        }
    },
})
