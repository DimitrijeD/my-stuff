<template>
    <div class="absolute left-0 bottom-0 h-full flex items-stretch invisible space-x-3 lg:mx-2">
        <ChatWindow
            v-for="group_id in openedGroupsIds"
            :key="group_id"
            :group_id="group_id"
        />
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import ChatWindow from "@/Components/Chat/ChatWindow/ChatWindow.vue";
import * as ns from '@/Store/module_namespaces.js'

export default {
    components:{ ChatWindow, },

    data(){
        return{

        }
    },

    computed: {
        ...mapGetters({ 
            user: "user",
            openedGroupsIds: ns.groupsManager('openedGroupsIds'),
        }),
    },

    beforeCreate(){

    },

    created(){
        this.$store.dispatch(ns.groupsManager('init')).then(()=>{
            this.$store.dispatch(ns.groupsManager('sortNewstGroups'))
            this.$store.dispatch(ns.groupsManager('numGroupsWithUnseen'))
        })

        this.listenUserToUserNotifications()
    },

    methods:
    {
        listenUserToUserNotifications(){
            Echo.private(`App.Models.User.${this.user.id}`)
            .listen('.message.notification', e => {
                this.$store.dispatch(ns.groupsManager('openGroup'), e.data.group_id)
            })
        },


    },
}
</script>

<style>

</style>