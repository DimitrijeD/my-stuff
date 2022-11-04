<template>
    <ChatWindow_v1Layout :group="group">
        <template #header>
            <Header :group="group" />
        </template>
                
        <template #messages>
            <MessagesBlock :group="group" @click="selfAcknowledged()" />
        </template>

        <template #action-response>
            <ActionResponseList 
                :moduleId="`groupId_${group_id}`" 
                :dieAfter="15" 
                :cardCls="'w-[90%] h-32 mx-auto'"
                class="mt-8 mx-auto w-full" 
            />
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
import ActionResponseList from '@/Components/ActionResponse/ActionResponseList.vue';

export default {
    props: [ 'group_id', ],

    components: {
        MessagesBlock,
        MessageInput,
        Config,
        ChatWindow_v1Layout,
        Header,
        ActionResponseList,
    },

    data(){
        return {
            permissions: {},
            config: {
                refreshGroupOnLoad: false
            },
            gm_ns: ns.groupModule(this.group_id), 
        }
    },

    computed: 
    {
        ...mapGetters({ 
            user: "user",
            rules:      ns.chat_rules('StateRules'),
            roles:      ns.chat_rules('StateRoles'),
            actionKeys: ns.chat_rules('StateKeys'),
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

            if(this.last_message.user_id == this.user.id) return

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

<style>

</style>