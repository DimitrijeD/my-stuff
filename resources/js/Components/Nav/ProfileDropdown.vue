<template>
    <div ref="wrap">
        <ThreeHorisontalParalelLinesIcon 
            @click="toggleClickListener" 
            class="nav-btn-svg" 
        />
        
        <div v-if="showDrop" class="relative ">
            <div class="def-dropdown top-0 right-0 p-2">
                <div class="w-[250px] flex flex-col space-y-1">
                    <router-link to="profile" @click="toggleClickListener" >
                        <SmallUser :userNameCls="'text-blue-500 hover-text-blue-600 dark:hover:text-blue-400'" :user="user" />
                    </router-link>

                    <router-link to="settings" class="profile-dropdown-list-item" @click="toggleClickListener" >Settings</router-link>

                    <Logout class="" @click="toggleClickListener"  />
                </div>
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

    mounted() {
        
    },

    methods: {

        clickOutside(e){
            if (this.$refs.wrap !==undefined && this.$refs.wrap.contains(e.target)===false) {
                this.removeClickListener()
            }
        },

        addClickListener(){
            document.addEventListener('click', this.clickOutside)
            this.showDrop = true
        },

        removeClickListener(){
            document.removeEventListener('click', this.clickOutside)
            this.showDrop = false
        },

        toggleClickListener(){
            this.showDrop
                ? this.removeClickListener()
                : this.addClickListener()
        },
    },

}

</script>