<template>
    <nav :class="['flex justify-between', headerHeight, anyDropsOpened() ? 'z-50 bg-gray-200 dark:bg-black relative' : '']">
        <!-- Left side of navigation bar -->
        <div class="flex justify-start relative">
            <router-link class="nav-btn" to="/">Home</router-link>
            <router-link  v-if="user" class="nav-btn" to="app-css-examples">Examples</router-link>
            <ChatDropdown v-if="user?.email_verified_at" class="px-2" @dropdownToggled="dropdownToggled" />
        </div>
        <!-- / -->

        <!-- Right side of navigation bar -->
        <div class="flex justify-end">
            <ProfileDropdown v-if="user" @dropdownToggled="dropdownToggled" />

            <button v-if="!user" class="nav-btn" @click="isDark = !isDark">{{isDark ? 'Light' : 'Dark'}}</button>

            <router-link v-if="!user" class="nav-btn" to="login"   >Login   </router-link>
            <router-link v-if="!user" class="nav-btn" to="register">Register</router-link>
        </div>
        <!-- / -->
    </nav>
</template>

<script>
import { mapGetters } from "vuex";
import { useDark, useToggle } from '@vueuse/core';
import Logout from '@/Pages/Auth/Logout.vue';
import ProfileDropdown from "@/Components/Nav/ProfileDropdown.vue";
import ChatDropdown from '@/Components/Chat/ChatDropdown/ChatDropdown.vue';

export default {
    inject: ['headerHeight'],

    components: { Logout, ProfileDropdown, ChatDropdown, },

    data(){
        return {
            isDark: useDark(),
            openedDrops: {
                chat: false,
                profile: false,
            },
        }
    },

    computed: {
        ...mapGetters({ user: "user" }),
    },

    methods: {
        dropdownToggled(data){
            this.openedDrops[data.name] = data.opened
        },

        anyDropsOpened(){
            for(let i in this.openedDrops){
                if(this.openedDrops[i]) return true
            }

            return false
        }
    },
}

</script>