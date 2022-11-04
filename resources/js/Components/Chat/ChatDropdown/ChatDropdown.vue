<template>
    <div :class="['relative']" ref="wrap">
        <MailIcon @click="toggleClickListener" :class="[ 'h-[3rem] w-[3rem] stroke-transparent', iconCls ]" />
        
        <div v-show="showDrop" class="def-dropdown fixed left-0 px-2 ml-1 w-[99%] h-[95%] md:w-[35rem] md:h-[40rem] md:left-auto lg:w-[40rem] lg:h-[40rem]">
            <div class="flex">
                <button :class="[ 'small-nav-btn ', showNav.ChatHistory ? 'small-nav-btn-active' : 'small-nav-btn-inactive', ]" @click="chatNav('ChatHistory')" >
                    Chat history
                </button>

                <button :class="[ 'small-nav-btn ', showNav.CreateChatGroup ? 'small-nav-btn-active' : 'small-nav-btn-inactive', ]" @click="chatNav('CreateChatGroup')">
                    Create new chat
                </button>
            </div>
            <div class="grow flex flex-col pt-2 ">
                <ChatHistory class="grow" v-show="showNav.ChatHistory" @closeDropdown="removeClickListener" />
                <CreateChatGroup class="grow" v-show="showNav.CreateChatGroup" @closeDropdown="removeClickListener" />
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
            showDrop: false,

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
            if(this.numGroupsWithUnseen) return 'fill-green-600/90'

            if(!this.numGroupsWithUnseen) return 'fill-blue-500/90 dark:fill-gray-300/80'

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