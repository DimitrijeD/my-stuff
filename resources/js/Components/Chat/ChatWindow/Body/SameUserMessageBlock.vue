<template>
    <div :class="['rounded-2xl p-1.5', isSelf ? 'bg-blue-200 dark:bg-darker-600/20' : 'bg-gray-200 dark:bg-darker-200'] " >
        <SmallUser
            :user="getUser(this.block.blockOwnerId)"
            :userNameCls="'text-gray-600 dark:text-gray-300 '"
            :imgCls="'w-10 h-10'"
            class="py-1"
        /> 

        <!-- List of messages in this block -->
        <div class="space-y-2 ">
            <div v-for="(message, index) in block.messages" :key="message.id">
                <Message :group="group" :message_id="message.id" :isSelf="isSelf" :user_id="user.id" />
                <hr v-if="block.messages?.[index+1]" class="w-full h-0.5 opacity-25 bg-gray-400 dark:bg-darker-400 border-0">
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

import * as ns from '@/Store/module_namespaces.js';
import SmallUser from   '@/Components/Reuseables/SmallUser.vue';
import Message from '@/Components/Chat/ChatWindow/Body/Message.vue'

export default {
    props: [ "block", "group" ],

    components:{ SmallUser, Message },

    data(){
        return {
            gm_ns: ns.groupModule(this.group.id),
        }
    },

    computed:{
        ...mapGetters({ 
            user: "user",
        }),

        blockOwner(){ return this.$store.getters[this.gm_ns + '/getParticipant'](this.block.blockOwnerId) },
        
        isSelf(){ return this.block.blockOwnerId == this.user.id },

    },

    methods: {
        getUser(id) { 
            const user = this.$store.getters[this.gm_ns + '/getParticipant'](id) 
            return user ? user : null
        },

    }


}
</script>