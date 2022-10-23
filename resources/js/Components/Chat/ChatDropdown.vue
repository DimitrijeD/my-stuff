<template>
    <div v-show="dropdown.isOpened" class="top-[3rem] grow flex flex-col px-2 z-10 border
        w-full h-full lg:w-[40rem] lg:h-[45rem]
        border-blue-400 bg-white dark:border-blue-400 dark:bg-darker-100"
    >
        <div class="flex">
            <button :class="[ 'small-nav-btn ', showNav.ChatHistory ? 'small-nav-btn-active' : 'small-nav-btn-inactive', ]" @click="chatNav('ChatHistory')"
            >Chat history</button>

            <button :class="[ 'small-nav-btn ', showNav.CreateChatGroup ? 'small-nav-btn-active' : 'small-nav-btn-inactive', ]" @click="chatNav('CreateChatGroup')"
            >Create new chat</button>
        </div>
        <div class="grow flex flex-col pt-2 ">
            <ChatHistory class="grow" v-show="showNav.ChatHistory" />
            <CreateChatGroup class="grow" v-show="showNav.CreateChatGroup" />
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex"
import * as ns from '@/Store/module_namespaces.js'
import CreateChatGroup from "@/Components/Chat/ChatDropdown/CreateChatGroup.vue";
import ChatHistory from '@/Components/Chat/ChatDropdown/ChatHistory.vue'

export default {
    
    components: {
        CreateChatGroup,
        ChatHistory,
    },

    data(){
        return {
            showNav: {
                CreateChatGroup: false,
                ChatHistory: true
            },
        }
    },

    created(){
        
    },

    computed: {
        ...mapGetters({ 
            dropdown: ns.groupsManager() + "/dropdown",
        }),
    },

    methods:
    {
        chatNav(componentName)
        {
            // First close (dont show) every component
            for(let i in this.showNav){
                this.showNav[i] = false
            }
            
            // then show one user clicked
            this.showNav[componentName] = true
        },

    },
}
</script>

<style>

</style>