<template>
    <div v-if="message" :class="[
        'py-1 rounded-xl clearfix relative def-trans', 
        !msgSeen ? 'bg-green-200 dark:bg-green-600/10' : '',
        // showTime ? 'border-l-2 border-black/50' : ''
    ]">
        <div :class="[isPendingDelete || isShowEdit ? '' : 'float-right flex py-1 pl-2 gap-1']">
            <span v-if="message?.edited" class="float-right text-sm font-extralight text-gray-400 px-1">edited</span>

            <EditMessage 
                v-if="isSelf"
                @showEdit="showEdit" 
                :message_id="message_id" 
                :key="`${message_id}_edit`" 
                :class="[isPendingDelete ? 'hidden' : '']" 
                :message="message"
            />

            <DeleteMessage 
                v-if="isSelf"
                @pendingDelete="pendingDelete" 
                :message_id="message_id" 
                :key="`${message_id}_delete`"
                :class="[isShowEdit ? 'hidden' : '']" 
            />
        </div>

        <p v-if="!isShowEdit" :class="[' px-2 font-light text-base text-gray-700 dark:text-gray-300 tracking-wide break-words', isPendingDelete ? 'blur-sm' : '']">
            {{ message?.text }}
            <br>
            <MomentsAgo v-if="showTime" :date="message?.created_at" class="def-moments-ago" />
        </p>

        <Transition v-if="!isShowEdit" name="fade" :class="[isPendingDelete ? 'blur-sm' : '']">
            <MessagesSeen 
                v-if="whoSawWhat[message_id]"
                :message_id="message_id"
                :message="message"
                :user_ids="whoSawWhat[message_id]"
            />
        </Transition>
    </div>
</template>

<script>
import MomentsAgo from '@/Components/Reuseables/MomentsAgo.vue';
import MessagesSeen from '@/Components/Chat/ChatWindow/Body/MessagesBlock/MessagesSeen.vue';
import DeleteMessage from '@/Components/Chat/ChatWindow/Body/MessagesBlock/Message/DeleteMessage.vue'
import EditMessage from '@/Components/Chat/ChatWindow/Body/MessagesBlock/Message/EditMessage.vue'

import { mapGetters } from "vuex";

// @todo message whitespace and new lines would be nice 

export default {
    inject: ['group_id'],

    props: [ 'message_id', 'isSelf', 'showTime' ],

    components: { MomentsAgo, MessagesSeen, DeleteMessage, EditMessage },

    data(){
        return {
            isPendingDelete: false,
            isShowEdit: false,
        }
    },

    computed: {
        ...mapGetters({ 
            user: "user",
        }),

        message(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'messageById') ](this.message_id)
        },

        messages_tracker(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'messages_tracker') ]
        },

        myLastMessageSeenId(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'myLastMessageSeenId') ]
        },

        whoSawWhat(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'whoSawWhat') ]
        },

        msgSeen(){
            if(this.messages_tracker.seen) return true

            if(this.myLastMessageSeenId < this.message_id) return false

            return true
        },
    },

    methods: {
        pendingDelete(bool){
            this.isPendingDelete = bool
        },

        showEdit(bool){
            this.isShowEdit = bool
        },
    }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: scaleY(0);
}

.clearfix:after { 
   content: " ";
   display: block; 
   height: 0; 
   clear: both;
}

</style>