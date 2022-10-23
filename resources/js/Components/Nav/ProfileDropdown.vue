<template>
    <div >
        <ThreeHorisontalParalelLinesIcon 
            @click="showDrop = !showDrop" 
            class="nav-btn-svg" 
        />
        
        <div v-if="showDrop" class="relative ">
            <div class="def-dropdown top-0 right-0">
                <div class="w-[250px] flex flex-col">
                    <router-link to="profile">
                        <SmallUser :userNameCls="'text-blue-500 hover-text-blue-600 dark:hover:text-blue-400'" :user="user" />
                    </router-link>

                    <button class="profile-dropdown-list-item" @click="isDark = !isDark">{{isDark ? 'Light' : 'Dark'}}</button>

                    <Logout class="" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Logout from '@/Pages/Auth/Logout.vue';
import SmallUser from '@/Components/Reuseables/SmallUser.vue';
import ThreeHorisontalParalelLinesIcon from "@/Components/Reuseables/Icons/ThreeHorisontalParalelLinesIcon.vue";
import { useDark, useToggle } from '@vueuse/core';

import { mapGetters } from "vuex";

export default {
    components: { Logout, SmallUser, ThreeHorisontalParalelLinesIcon, },

    data(){
        return {
            showDrop: false,
            isDark: useDark(),
        }
    },

    computed: {
        ...mapGetters({ user: "user" }),
    },
}

</script>