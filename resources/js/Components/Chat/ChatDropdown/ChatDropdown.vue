<template>
    <div :class="['relative']">
        <MailIcon  @click="showDropdown = !showDropdown" :class="[ 'h-[3rem] w-[3rem] stroke-transparent', iconCls ]" />
        
        <div v-show="showDropdown" class="overflow-hidden fixed left-0 flex flex-col px-2 z-10 border
            ml-1 w-[99%] h-[95%] md:w-[35rem] md:h-[40rem] md:left-auto lg:w-[40rem] lg:h-[40rem]
            border-blue-400 bg-white dark:border-blue-400 dark:bg-darker-100">

            <div class="flex">
                <button :class="[ 'small-nav-btn ', showNav.ChatHistory ? 'small-nav-btn-active' : 'small-nav-btn-inactive', ]" @click="chatNav('ChatHistory')" >
                    Chat history
                </button>

                <button :class="[ 'small-nav-btn ', showNav.CreateChatGroup ? 'small-nav-btn-active' : 'small-nav-btn-inactive', ]" @click="chatNav('CreateChatGroup')">
                    Create new chat
                </button>
            </div>
            <div class="grow flex flex-col pt-2 ">
                <ChatHistory class="grow" v-show="showNav.ChatHistory" @closeDropdown="showDropdown = !showDropdown" />
                <CreateChatGroup class="grow" v-show="showNav.CreateChatGroup" @closeDropdown="showDropdown = !showDropdown" />
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex"
import * as ns from '@/Store/module_namespaces.js'
import CreateChatGroup from "@/Components/Chat/ChatDropdown/CreateChatGroup.vue";
import ChatHistory from '@/Components/Chat/ChatDropdown/ChatHistory.vue'
import MailIcon from "@/Components/Reuseables/Icons/MailIcon.vue"

export default {
    components: { CreateChatGroup, ChatHistory, MailIcon },

    data(){
        return {
            showDropdown: false,

            showNav: {
                CreateChatGroup: false,
                ChatHistory: true
            },
        }
    },

    computed: {
        ...mapGetters({ 
            numGroupsWithUnseen: ns.groupsManager('numGroupsWithUnseen'),
        }),

        iconCls(){
            if(this.numGroupsWithUnseen && !this.showDropdown) {
                return 'fill-green-400'
            }

            if(this.numGroupsWithUnseen && this.showDropdown) {
                return 'fill-green-600'
            }

            if(!this.numGroupsWithUnseen && !this.showDropdown) {
                return 'fill-blue-500'
            }

            if(!this.numGroupsWithUnseen && this.showDropdown) {
                return 'fill-blue-600'
            }

            return 'fill-gray-400' 
        }
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