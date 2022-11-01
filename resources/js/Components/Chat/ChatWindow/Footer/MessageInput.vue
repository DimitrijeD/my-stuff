<template>
    <div class="dark:bg-gradient-to-t dark:bg-transparent dark:from-neutral-800 px-2 pb-2 flex gap-2">
        <textarea
            class="flex-grow p-4 resize-none rounded-2xl outline-none bg-white border border-blue-400 text-gray-700 dark:bg-darker-400 dark:text-gray-300 dark:border-none"
            rows="3"
            @keyup.enter.exact.prevent="sendMessageEvent()"
            @keydown.enter.shift.exact.prevent="message += '\n'"
            @keydown.exact="userTyping"
            type="text"
            v-model="message"
            placeholder="Message ..."
        ></textarea>

        <SendMessageIcon @click="sendMessageEvent()" class="w-11 hover:scale-110 fill-blue-100 stroke-blue-500 dark:fill-darker-300 dark:stroke-blue-500 " />
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import * as ns from '@/Store/module_namespaces.js'
import SendMessageIcon from "@/Components/Reuseables/Icons/SendMessageIcon.vue"

export default {
    props:[ 'group_id', ],

    components: { SendMessageIcon, },

    data(){
        return{
            message: '',
            gm_ns: ns.groupModule(this.group_id),
        }
    },

    computed: {
        ...mapGetters({ user: "user" }),

    },

    methods: {
        sendMessageEvent(){
            this.userStopedTyping()
            this.sendMessage()
        },

        sendMessage(){
            if(this.message === '' || this.message.trim() == '') return

            this.$store.dispatch(this.gm_ns + '/storeMessage', this.getMessageFormat()).then(()=> {
                this.message = ''
            }).catch(error => {

            })
        },

        userTyping(){
            Echo.private("group." + this.group_id).whisper("typing", this.getWhisperData())
        },

        userStopedTyping(){
            Echo.private("group." + this.group_id).whisper("stoped-typing", this.getWhisperData())
        },

        getMessageFormat(){
            return {
                text: this.message,
                group_id: this.group_id,
                user_id: this.user.id
            };
        },

        getWhisperData(){
            return {
                'id': this.user.id,
                'first_name': this.user.first_name,
                'last_name': this.user.last_name,
            }
        },
    }
}
</script>