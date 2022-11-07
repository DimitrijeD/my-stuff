/* ------------------------Pages------------------------ */

/* ----- Auth ----- */
import Register from '@/Pages/Auth/Register.vue';
import Login from '@/Pages/Auth/Login.vue';
import ForgotPassword from '@/Pages/Auth/ForgotPassword.vue';
import PasswordReset from '@/Pages/Auth/PasswordReset.vue';
import EmailVerification from '@/Pages/Auth/EmailVerification.vue';
import EmailVerificationAttempt from '@/Pages/Auth/EmailVerificationAttempt.vue';
/* ---------------- */

import Profile from '@/Pages/Profile.vue';
import AppCSSExamples from '@/Pages/AppCSSExamples/AppCSSExamples.vue';
import Home from '@/Pages/Home.vue';
import NotFound from '@/Pages/NotFound.vue';
import Settings from '@/Pages/Settings/Settings.vue';

/* ----------------------------------------------------------- */

/* ------------------------Middlewares------------------------ */
import auth from '@/Router/middleware/auth.js';
import auth_verified from '@/Router/middleware/auth_verified.js';
import auth_not_verified from '@/Router/middleware/auth_not_verified.js';
import guest from '@/Router/middleware/guest.js';

/* ----------------------------------------------------------- */

export default [
    {
        path: "/:pathMatch(.*)*",
        name: "NotFound",
        component: NotFound,
    },
    {
        path: '/',
        name: "Home",
        component: Home,
    },
    {
        path: '/register',
        name: "Register",
        component: Register,
        meta: { middleware: [guest] },
    },    
    {
        path: '/forgot-password',
        name: "ForgotPassword",
        component: ForgotPassword,
        meta: { middleware: [guest] },
    },
    {
        path: '/reset-password',
        name: "PasswordReset",
        component: PasswordReset,
    },
    {
        path: '/login',
        name: "Login",
        component: Login,
        meta: { middleware: [guest] },
    },
    {
        path: '/email-verification/init',
        name: "EmailVerification",
        component: EmailVerification,
        meta: { middleware: [auth_not_verified] },
    },
    {
        path: '/email-verification/uid/:user_id/c/:code',
        name: "EmailVerificationAttempt",
        component: EmailVerificationAttempt,
        props: true,
        meta: { middleware: [auth_not_verified] },
    },
    {
        path: '/settings',
        name: "Settings",
        component: Settings,
        meta: { middleware: [auth] },
    },
    {
        path: '/profile',
        name: "Profile",
        component: Profile,
        meta: { middleware: [auth_verified] },
    },
    {
        path: '/app-css-examples',
        name: "AppCSSExamples",
        component: AppCSSExamples,
        meta: { middleware: [auth_verified] },
    },
]