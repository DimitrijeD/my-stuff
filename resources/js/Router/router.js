import { createRouter, createWebHistory } from 'vue-router';
import middlewarePipeline from './middlewarePipeline';
import routes from './routes';
import store from '@/Store/index.js';

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
})

router.beforeEach((to, from, next) => {
    const middleware = to.meta.middleware
    const context = { to, from, next, router, store }

    // if middleware is not defined Or if middleware is empty
    if (!middleware || middleware.length == 0) return next()

    return middleware[0]({
        ...context,
        next: middlewarePipeline(context, middleware, 1),
    })
})

export default router