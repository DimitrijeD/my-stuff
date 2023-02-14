import './bootstrap';

// const token = localStorage.getItem('token');

import { createApp } from 'vue';

import App from "@/App.vue";
import router from '@/Router/router.js';
import store from "@/Store/index.js";

import clickAway from '@/Directives/ClickAway.js'


let app = createApp(App);

app
    .use(store)
    .use(router)
    .directive("click-away", clickAway)
    .mount("#app")
