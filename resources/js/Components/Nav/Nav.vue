<template>
    <nav class="flex justify-between h-[3rem] ">
        <!-- Left side of navigation bar -->
        <div class="flex justify-start relative">
            <router-link active-class="nav-btn-active" class="nav-btn" to="/">Home</router-link>
            <router-link v-if="user" active-class="nav-btn-active" class="nav-btn" to="app-css-examples">Examples</router-link>
            <ChatDropdown v-if="user?.email_verified_at" class="px-2" />
        </div>

        <!-- Right side of navigation bar -->
        <div class="flex justify-end ">
            <ProfileDropdown v-if="user" />

            <button v-if="!user" class="nav-btn" @click="isDark = !isDark">{{isDark ? 'Light' : 'Dark'}}</button>
            <router-link v-if="!user" active-class="nav-btn-active" class="nav-btn" to="login"   >Login   </router-link>
            <router-link v-if="!user" active-class="nav-btn-active" class="nav-btn" to="register">Register</router-link>
        </div>

    </nav>
</template>

<script>
import { mapGetters } from "vuex";
import { useDark, useToggle } from '@vueuse/core';
import Logout from '@/Pages/Auth/Logout.vue';
import ProfileDropdown from "@/Components/Nav/ProfileDropdown.vue";
import ChatDropdown from '@/Components/Chat/ChatDropdown/ChatDropdown.vue';

export default {
    components: {
        Logout,
        ProfileDropdown,
        ChatDropdown,
    },

    data(){
        return {
            isDark: useDark(),
        }
    },

    computed: {
        ...mapGetters({ user: "user" }),
    },

    methods: {

    }

}

</script>


<style >


</style>