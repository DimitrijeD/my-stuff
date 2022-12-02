<template>
    <div ref="wrap" v-click-away="clickAway">
        <ThreeHorisontalParalelLinesIcon @click="toggle" class="nav-btn-svg" />
        
        <div v-if="showDrop" class="relative">
            <div class="def-dropdown right-0 w-full h-full sm:h-max sm:w-[300px] flex flex-col space-y-2 ">
                <router-link to="profile" @click="toggle" class="pt-2">
                    <SmallUser :userNameCls="'text-blue-500'" :user="user" />
                </router-link>

                <router-link to="settings" class="profile-dropdown-list-item" @click="toggle">Settings</router-link>

                <Logout @click="toggle" />
            </div>
        </div>
    </div>
</template>

<script>
import Logout from '@/Pages/Auth/Logout.vue';
import SmallUser from '@/Components/Reuseables/SmallUser.vue';
import ThreeHorisontalParalelLinesIcon from "@/Components/Reuseables/Icons/ThreeHorisontalParalelLinesIcon.vue";
import { mapGetters } from "vuex";

export default {
    components: { Logout, SmallUser, ThreeHorisontalParalelLinesIcon, },

    data(){
        return {
            showDrop: false,
        }
    },

    computed: {
        ...mapGetters({ user: "user" }),
    },

    methods: {
        clickAway(){
            this.showDrop = false
        },

        toggle(){
            this.showDrop = !this.showDrop
        },

    },

}

</script>