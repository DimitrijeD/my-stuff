<template>
    <ChatWindow_v1Layout :size="size" >
        <template #header>
            <Header />
        </template>
                
        <template #messages>
            <MessagesBlock @click="selfAcknowledged()" />
        </template>

        <template #action-response>
            <ActionResponseList 
                :moduleId="`groupId_${group_id}`" 
                :dieAfter="15" 
                :cardCls="'w-[90%] h-32 mx-auto'"
                class="mt-8 mx-auto w-full" 
            />
        </template>

        <template #config>
            <Config v-show="window.showConfig" />
        </template>

        <template #footer>
            <MessageInput v-show="canSendMessage" @click="selfAcknowledged()" />
        </template>
    </ChatWindow_v1Layout>
</template>

<script>
import { mapGetters } from 'vuex'
import ChatWindow_v1Layout from '@/Layouts/Chat/ChatWindow_v1Layout.vue'
import Header from "@/Components/Chat/ChatWindow/Header/Header.vue"

import MessagesBlock    from "@/Components/Chat/ChatWindow/Body/MessagesBlock/MessagesBlock.vue"
import Config           from "@/Components/Chat/ChatWindow/Body/Config/Config.vue"
import MessageInput     from "@/Components/Chat/ChatWindow/Footer/MessageInput.vue"
import ActionResponseList from '@/Components/ActionResponse/ActionResponseList.vue'

export default {
    props: [ 'group_id', 'size' ],

    provide() {
        return {
            group_id: this.group_id
        }
    },

    components: { MessagesBlock, MessageInput, Config, ChatWindow_v1Layout, Header, ActionResponseList, },

    computed: {
        ...mapGetters({ 
            user_id: "user_id",
        }),

        window(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'window') ]
        },

        last_message(){ 
            return this.$store.getters[ ns.groupModule(this.group_id, 'last_message') ] 
        },

        seen(){ 
            return this.$store.getters[ ns.groupModule(this.group_id, 'seen') ]
        },

        canSendMessage(){ 
            return this.$store.getters[ ns.groupModule(this.group_id, 'canSendMessage') ]
        },
    },

    created(){
        this.$store.dispatch(ns.groupModule(this.group_id, 'getInitMessages'))
    },

    methods: {   
        selfAcknowledged(){
            if(this.seen) return

            if(this.last_message.user_id == this.user_id) return

            this.$store.dispatch(  ns.groupModule(this.group_id, 'allMessagesSeen') , this.last_message.id)
        },
    }
}

</script>