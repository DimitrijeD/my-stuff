<template>
    <GroupCardLayout v-if="group_id" :seen="seen" :atLeastTwoParticpants="atLeastTwoParticpants" :groupName="name">
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
</template>

<script>
import { mapGetters } from "vuex"

import SmallUser from   '@/Components/Reuseables/SmallUser.vue';
import MessageCard from "@/Components/Chat/reuseables/MessageCard.vue"
import GroupCardLayout from '@/Layouts/GroupCardLayout.vue'

export default {
    props: [ 'group_id', ],

    components: { SmallUser, MessageCard, GroupCardLayout, },

    computed:{
        ...mapGetters({ 
            user: "user",
        }),

        participants(){
            return this.$store.getters[ns.groupModule(this.group_id, 'participants')]
        },

        name(){
            return this.$store.getters[ns.groupModule(this.group_id, 'name')] 
        },

        lastMessage(){ 
            return this.$store.getters[ns.groupModule(this.group_id, 'last_message')] 
        },

        atLeastTwoParticpants(){ 
            return Object.keys(this.participants).length >= 2 
        },

        seen(){ 
            return this.$store.getters[ns.groupModule(this.group_id, 'seen')] 
        },

        numUnseenMsges(){ 
            return this.$store.getters[this.group_id, 'numUnseenMsges'] 
        },

    },

    methods: {
        hasLastMessage(msg){ return msg.hasOwnProperty('id') ? true : false },
    },
}
</script>