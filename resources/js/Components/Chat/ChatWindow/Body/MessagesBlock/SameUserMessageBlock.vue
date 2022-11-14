<template>
    <div :class="['rounded-2xl p-1.5', isSelf 
        ? 'self-shadow bg-blue-200 dark:bg-darker-600/20' 
        : 'notself-shadow bg-gray-200 dark:bg-darker-200']"
    >
        <SmallUser
            :user="getUser(this.block.blockOwnerId)"
            :imgCls="'w-10 h-10'"
            class="py-1"
        /> 

        <!-- List of messages in this block -->
        <TransitionGroup tag="div" name="list" class=" space-y-2 relative">
            <Message 
                v-for="(message, index) in block.messages" 
                :key="message.id" 
                :group="group" 
                :message_id="message.id" 
                :isSelf="isSelf" 
                :user_id="user.id" 
            />
        </TransitionGroup>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

import * as ns from '@/Store/module_namespaces.js';
import SmallUser from   '@/Components/Reuseables/SmallUser.vue';
import Message from '@/Components/Chat/ChatWindow/Body/MessagesBlock/Message.vue'

export default {
    props: [ "block", "group" ],

    components:{ SmallUser, Message,},

    computed:{
        ...mapGetters({ 
            user: "user",
        }),

        blockOwner(){ return this.$store.getters[ns.groupModule(this.group.id, 'getParticipant')](this.block.blockOwnerId) },
        
        isSelf(){ return this.block.blockOwnerId == this.user.id },

    },

    methods: {
        getUser(id) { 
            const user = this.$store.getters[ns.groupModule(this.group.id, 'getParticipant')](id) 
            return user ? user : null
        },
    }
}
</script>

<style scoped>
    .list-move,
    .list-enter-active,
    .list-leave-active{
        transition: all 0.8s cubic-bezier(0.55, 0, 0.1, 1);
    }
    
    .list-enter-from,
    .list-leave-to{
        opacity: 0;
        transform: scaleY(0.01) translate(40px, 0);
    }
    
    .list-leave-active {
        position: absolute;
        width: 100%;
        
    }

    .self-shadow {
        box-shadow: -3px 2px 2px 1px rgba(0, 0, 0, 0.2);
    }
    .notself-shadow {
        box-shadow: -1px 1px 1px 1px rgba(6, 2, 125, 0.2); 
    }
</style>