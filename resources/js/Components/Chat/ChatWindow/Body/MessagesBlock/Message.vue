<template>
    <div  class=" px-2 pb-2 rounded-2xl def-trans"
        :class="[ 
            isSelf ? '' : '',
            msgNotSeen(message_id) && !isSelf ? 'bg-green-200 dark:bg-green-600/20' : '',
        ]"
    >
        <!-- @TODO Need delete message component here with SVG icon -->
        <!-- <span class="float-right text-sm rounded-full border border-gray-200 cursor-pointer border hover:border-red-300">X</span> -->

        <!-- Message Content style="white-space: pre;" -->
        <!-- @TODO - caused issue where messages woudnt break into new line but it did include new lines and tabs... -->
        <p  class="font-serif mt-0.5 text-lg text-gray-700 dark:text-gray-300 tracking-wide break-words">
            <MomentsAgo :date="group.messages[message_id].created_at" class="def-moments-ago float-right pl-2 pb-2" />
            {{ group.messages[message_id].text }}
            
        </p>
        <!-- / -->

        <MessagesSeen 
            v-if="group.whoSawWhat[message_id]"
            :message_id="message_id"
            :group_id="group.id"
            :message="group.messages[message_id]"
            :user_ids="group.whoSawWhat[message_id]"
        />
    </div>
</template>

<script>
import MomentsAgo from '@/Components/Reuseables/MomentsAgo.vue';
import MessagesSeen from '@/Components/Chat/ChatWindow/Body/MessagesBlock/MessagesSeen.vue';

export default {
    props: [ 'group', 'message_id', 'isSelf', 'user_id' ],

    components: { MomentsAgo, MessagesSeen },

    data(){
        return {

        }
    },

    methods: {
        msgNotSeen(msg_id){
            if(this.group.messages_tracker.seen) return false

            if(this.group.participants[this.user_id].pivot.last_message_seen_id < msg_id) return true

            return false
        },

    }
}
</script>