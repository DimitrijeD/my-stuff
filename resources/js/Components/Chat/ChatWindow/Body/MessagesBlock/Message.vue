<template>
    <div :class="['px-2 pb-0.5 rounded-2xl def-trans', msgNotSeen() && !isSelf ? 'bg-green-200 dark:bg-green-600/20' : '']">
        <!-- @TODO Need delete message component here with SVG icon -->
        <!-- <span class="float-right text-sm rounded-full border border-gray-200 cursor-pointer border hover:border-red-300">X</span> -->

        <!-- Message Content style="white-space: pre;" -->
        <!-- @TODO - caused issue where messages woudnt break into new line but it did include new lines and tabs... -->
        <p  class="my-1 font-light text-base text-gray-700 dark:text-gray-300 tracking-wide break-words">
            <MomentsAgo v-if="showTime" :date="message.created_at" class="def-moments-ago float-right pl-2 " />
            {{ message.text }}
        </p>

        <Transition name="fade">
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
import { mapGetters } from "vuex";

export default {
    inject: ['group_id'],

    props: [ 'message_id', 'isSelf', 'showTime' ],

    components: { MomentsAgo, MessagesSeen },

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

        last_message_seen_id(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'last_message_seen_id') ](this.user.id)
        },

        whoSawWhat(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'whoSawWhat') ]
        },

    },

    methods: {
        msgNotSeen(){
            if(this.messages_tracker.seen) return false

            if(this.last_message_seen_id < this.message_id) return true

            return false
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

</style>