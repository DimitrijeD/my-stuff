<template>
    <ChatWindow_v1Layout :group="group">
        <template #header>
            <Header :group="group" />
        </template>
                
        <template #messages>
            <MessagesBlock :group="group" @click="selfAcknowledged()" />
        </template>

        <template #config>
            <Config 
                v-show="group.window.showConfig "
                :group="group"
                :permissions="permissions"
                :chatRole="chatRole"
                :roles="roles"
            />
        </template>

        <template #footer>
            <MessageInput 
                v-show="permissions.send_message" 
                :group_id="group.id" 
                @click="selfAcknowledged()"
            />
        </template>
    </ChatWindow_v1Layout>
</template>

<script>

import { mapGetters } from 'vuex'
import ChatWindow_v1Layout from '@/Layouts/ChatWindow_v1Layout.vue'
import Header from "@/Components/Chat/ChatWindow/Header/Header.vue"

import MessagesBlock    from "@/Components/Chat/ChatWindow/Body/MessagesBlock.vue"
import Config           from "@/Components/Chat/ChatWindow/Body/Config.vue"
import MessageInput     from "@/Components/Chat/ChatWindow/Footer/MessageInput.vue"
import * as ns from '@/Store/module_namespaces.js'
import { Permissions } from '@/Components/Chat/policies/permisions.js'

export default {
    props: [ 'group_id', ],

    components: {
        MessagesBlock,
        MessageInput,
        Config,
        ChatWindow_v1Layout,
        Header,
    },

    data(){
        return {
            permissions: {},
            config: {
                refreshGroupOnLoad: false
            },
            gm_ns: ns.groupModule(this.group_id), // group module name space
        }
    },

    computed: 
    {
        ...mapGetters({ 
            user: "user",
            rules:      ns.chat_rules() + "/StateRules",
            roles:      ns.chat_rules() + "/StateRoles",
            actionKeys: ns.chat_rules() + "/StateKeys",
        }),

        group(){ return this.$store.getters[this.gm_ns + '/state']},

        chatRole(){ return this.$store.getters[this.gm_ns + '/getUserRole'](this.user.id) },

        last_message(){  return this.$store.getters[`${this.gm_ns}/last_message`] },

        seen(){ return this.$store.getters[`${this.gm_ns}/seen`]},
    },

    watch: {
        chatRole: function () {
            this.createPermissions()
            // @todo call event here: User, ur role has been changed
        },
    },

    created() 
    {
        this.createPermissions()
        // this.initGroup()

        this.$store.dispatch(this.gm_ns + '/registerEventListeners')
        this.getInitMessages()

    },

    methods: {   
        createPermissions(){
            this.permissions = (new Permissions(this.actionKeys, this.rules, this.chatRole, this.group, this.user)).createPermissions()
        },

        selfAcknowledged(){
            if(this.seen) return

            if(this.last_message.user_id == this.user.id){
                // console.log('CW, seen state is false meaning I didnt see last msg. this is shown because last message owner is me.')
                // console.log('this will cause FE issue:')
                // console.log('bg-green but messages are seen, and I did click Chat Window')
                return
            }
            this.$store.dispatch(this.gm_ns + '/allMessagesSeen', this.last_message.id)
        },

        initGroup(){
            if(this.config.refreshGroupOnLoad) this.$store.dispatch(this.gm_ns + '/refreshGroup', {group_id: this.group_id})
        },

        // if this group has less then N num of messages, store will trigger API for more messages
        getInitMessages(){ 
            this.$store.dispatch(this.gm_ns + '/getInitMessages').then(() => {
                this.$store.dispatch(`${this.gm_ns}/whoSawWhat`)
            })
        },

    }
}

</script>