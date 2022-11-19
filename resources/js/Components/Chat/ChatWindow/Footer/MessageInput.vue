<template>
    <div class="dark:bg-gradient-to-t dark:bg-transparent dark:from-neutral-800 p-2 flex gap-2">
        <textarea
            class="flex-grow p-4 resize-none rounded-2xl outline-none bg-white border border-blue-400 text-gray-700 dark:bg-darker-400 dark:text-gray-300 dark:border-none overflow-y-auto scroll2"
            rows="3"
            @keyup.enter.exact.prevent="sendMessageEvent()"
            @keydown.enter.shift.exact.prevent="message += '\n'"
            @keydown="typingWhisper(true)"
            type="text"
            v-model="message"
            placeholder="Message ..."
        ></textarea>

        <SendMessageIcon @click="sendMessageEvent()" class="w-11 hover:scale-110 fill-blue-100 stroke-blue-500 dark:fill-darker-300 dark:stroke-blue-500 " />
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import SendMessageIcon from "@/Components/Reuseables/Icons/SendMessageIcon.vue"

/**
 * @todo while typing normal text into input all good, but if user clicks non text buttons, @keydown still registeres it as typing and dispatches event 
 */

export default {
    inject: ['group_id'],

    components: { SendMessageIcon, },

    data(){
        return{
            message: '',
        }
    },

    computed: {
        ...mapGetters({ user: "user" }),
    },

    methods: {
        sendMessageEvent(){
            this.typingWhisper(false)
            this.sendMessage()
        },

        sendMessage(){
            if(this.message === '' || this.message.trim() == '') return

            this.$store.dispatch(ns.groupModule(this.group_id, 'storeMessage'), this.messagePayload()).then(()=> {
                this.message = ''
            })
        },

        typingWhisper(isTyping){
            Echo.private("group." + this.group_id).whisper("typing", {
                id: this.user.id,
                isTyping: isTyping
            })
        },

        messagePayload(){
            return {
                text: this.message,
                group_id: this.group_id,
                user_id: this.user.id
            };
        },
    }
}
</script>

<style scoped>

</style>