<template>
    <div v-if="message" @click="showMessageOptions()" :class="[
        'rounded-xl  relative def-trans', 
        !msgSeen && !isSelf ? 'bg-green-200 dark:bg-green-600/10 hover:bg-bg-green-300 dark:bg-green-600/20' : 'hover:bg-gray-300 dark:hover:bg-darker-300',
        !isPendingDelete && !isShowEdit ? 'py-1' : '',
        // showTime ? 'border-l-2 border-black/50' : '' clearfix
    ]">
        <div :class="[isPendingDelete || isShowEdit ? '' : 'float-right flex py-0.5 pl-2 gap-1']">
            <span v-if="message?.edited" class="float-right text-sm font-extralight text-gray-400 px-1 select-none">edited</span>

            <EditMessage 
                v-if="isSelf && more"
                @showEdit="showEdit" 
                :message_id="message_id" 
                :key="`${message_id}_edit`" 
                :class="[isPendingDelete ? 'hidden' : '']" 
                :message="message"
            />

            <DeleteMessage 
                v-if="isSelf && more"
                @pendingDelete="pendingDelete" 
                :message_id="message_id" 
                :key="`${message_id}_delete`"
                :class="[isShowEdit ? 'hidden' : '']" 
            />
        </div>

        <div v-if="!isShowEdit" :class="[' px-2 font-light text-base text-gray-700 dark:text-gray-300 tracking-wide break-words', isPendingDelete ? 'blur-sm' : '']">
            <p>
                {{ message?.text }}
            </p>

            <MessageFilesList :message_id="message_id" />

            <MomentsAgo v-if="showTime" :date="message?.created_at" class="def-moments-ago" />
        </div>

        <MessagesSeen 
            v-show="whoSawWhat[message_id] && !isShowEdit"
            :message_id="message_id"
            :message="message"
            :user_ids="whoSawWhat[message_id]"
            :class="[isPendingDelete ? 'blur-sm' : '']"
        />
    </div>
</template>

<script>
import MomentsAgo from '@/Components/Reuseables/MomentsAgo.vue';
import MessagesSeen from '@/Components/Chat/ChatWindow/Body/MessagesBlock/MessagesSeen.vue';
import DeleteMessage from '@/Components/Chat/ChatWindow/Body/MessagesBlock/Message/DeleteMessage.vue'
import EditMessage from '@/Components/Chat/ChatWindow/Body/MessagesBlock/Message/EditMessage.vue'
import MessageFilesList from '@/Components/Chat/ChatWindow/Body/MessagesBlock/Message/MessageFilesList.vue'

import { mapGetters } from "vuex";

// @todo message whitespace and new lines would be nice 
// transitions, reasonable if statements in this component,
// why does conditional class toggle in Transition component trigger transition it self 

export default {
    inject: ['group_id'],

    props: [ 'message_id', 'isSelf', 'showTime' ],

    components: { MomentsAgo, MessagesSeen, DeleteMessage, EditMessage, MessageFilesList },

    data(){
        return {
            isPendingDelete: false,
            isShowEdit: false,
            more: false
        }
    },

    computed: {
        message(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'messagesM/messageById') ](this.message_id);
        },

        messages_tracker(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'messagesM/messages_tracker') ];
        },

        myLastMessageSeenId(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'participantsM/myLastMessageSeenId') ];
        },

        whoSawWhat(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'messagesM/whoSawWhat') ];
        },

        msgSeen(){
            if(this.messages_tracker.seen) return true;

            if(this.myLastMessageSeenId < this.message_id) return false;

            return true;
        },
    },

    methods: {
        pendingDelete(bool){
            this.isPendingDelete = bool;
        },

        showEdit(bool){
            this.isShowEdit = bool;
        },

        showMessageOptions(){
            this.more = true;
        }
    }
}
</script>

<style scoped>

/* .clearfix:after { 
   content: " ";
   display: block; 
   height: 0; 
   clear: both;
} */

</style>