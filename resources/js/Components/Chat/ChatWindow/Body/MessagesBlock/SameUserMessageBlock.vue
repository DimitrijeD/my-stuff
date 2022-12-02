<template>
    <div :class="['rounded-2xl p-1.5 space-y-1', isSelf 
        ? 'self-shadow bg-blue-200 dark:bg-darker-600/20' 
        : 'notself-shadow bg-gray-200 dark:bg-darker-200']"
    >
        <SmallUser
            :user="getUser(this.block.blockOwnerId)"
            :imgCls="'w-12 h-12 img-shadow'"
            :layoutCls="'gap-1'"
            class="py-1"
        /> 

        <TransitionGroup tag="div" name="list" class=" space-y-3 relative">
            <Message 
                v-for="(message_id, index) in block.messages" 
                :key="index" 
                :message_id="message_id" 
                :showTime="block.showTime.includes(message_id)"
                :isSelf="isSelf" 
            />
        </TransitionGroup>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

import SmallUser from   '@/Components/Reuseables/SmallUser.vue';
import Message from '@/Components/Chat/ChatWindow/Body/MessagesBlock/Message/Message.vue'

export default {
    inject: ['group_id'],

    props: [ "block", ],

    components:{ SmallUser, Message,},

    computed:{
        ...mapGetters({ 
            user: "user",
        }),

        blockOwner(){ 
            return this.$store.getters[ns.groupModule(this.group_id, 'participantsM/getParticipant')](this.block.blockOwnerId) 
        },
        
        isSelf(){ 
            return this.block.blockOwnerId == this.user.id 
        },
    },

    methods: {
        getUser(id) { 
            const user = this.$store.getters[ns.groupModule(this.group_id, 'participantsM/getParticipant')](id) 
            return user ? user : null
        },
    }
}
</script>

<style scoped>
.list-move,
.list-enter-active,
.list-leave-active{
    transition: all .2s ease-in;
}

.list-leave-to,
.list-enter-from{
    opacity: 0;
    transform: scaleY(0.01) translateX(20px);
}

.list-leave-active {
    position: absolute;
    width: 100%;
}

.self-shadow {
    box-shadow: -3px 2px 2px 1px rgba(0, 0, 0, 0.1), 0 -1px 1px 1px rgba(0, 0, 0, 0.05);
}
.notself-shadow {
    box-shadow: -1px 1px 1px 1px rgba(0, 0, 0, 0.4), 2px -2px 2px 0 rgba(0, 0, 0, 0.2); 
}

</style>