<template>
    <GroupCardLayout v-if="getMyAccepted" :seen="seen" :atLeastTwoParticpants="atLeastTwoParticpants" :groupName="name" @click.native="openChatWindow()">
        <template #group-name>
            {{name ? name : null}}
        </template>

        <template #participants>
            <div v-for="p in participants">
                <SmallUser 
                    v-if="p.id !== user.id"
                    :user="p" 
                    :imgCls="'w-9 h-9'" 
                    :layoutCls="'pb-1 pl-1'"
                />
            </div>
        </template>

        <template #last-message>
            <MessageCard :group_id="group_id" :seen="seen" />
        </template>
        
    </GroupCardLayout>

    <PendingAcceptGroupCard
        v-else 
        :group_id="group_id"
    />
</template>

<script>
import { mapGetters } from "vuex"

import SmallUser from   '@/Components/Reuseables/SmallUser.vue';
import MessageCard from "@/Components/Chat/reuseables/MessageCard.vue"
import GroupCardLayout from '@/Layouts/GroupCardLayout.vue'
import PendingAcceptGroupCard from "@/Components/Chat/reuseables/PendingAcceptGroupCard.vue"

export default {
    props: [ 'group_id', ],

    components: { SmallUser, MessageCard, GroupCardLayout, PendingAcceptGroupCard, },

    computed:{
        ...mapGetters({ 
            user: "user",
        }),

        participants(){
            return this.$store.getters[ns.groupModule(this.group_id, 'participantsM/participants')]
        },

        name(){
            return this.$store.getters[ns.groupModule(this.group_id, 'name')] 
        },

        atLeastTwoParticpants(){ 
            return Object.keys(this.participants).length >= 2 
        },

        seen(){ 
            return this.$store.getters[ns.groupModule(this.group_id, 'messagesM/seen')] 
        },

        getMyAccepted(){
            return this.$store.getters[ns.groupModule(this.group_id, 'participantsM/getMyAccepted')] 
        },
    },

    methods: {
        openChatWindow(){
            this.$store.dispatch(ns.groupsManager('openGroup'), {group_id: this.group_id, initiatedBy: 'user'}).then(() =>{
                this.$store.dispatch(ns.chatDropdown('toggle'))
            })
        },
    },
}
</script>