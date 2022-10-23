import './bootstrap';

import { createApp } from 'vue';

import App from "@/App.vue";
import router from '@/Router/router.js';
import store from "@/Store/index.js";

let app = createApp(App);

app
    .use(store)
    .use(router)
    .mount("#app")
