<template>
    <GroupCardLayout v-if="group" :seen="seen" :atLeastTwoParticpants="atLeastTwoParticpants" :groupName="group.name">
        <template #group-name>
            {{group.name ? group.name : null}}
        </template>

        <template #participants>
            <div v-for="p in group.participants">
                <SmallUser 
                    v-if="p.id !== user.id"
                    :user="p" 
                    :imgCls="'w-9 h-9'" 
                    :layoutCls="'pb-1 pl-1'"
                />
            </div>
        </template>

        <template #last-message>
            <MessageCard 
                v-if="lastMessage"
                :message="lastMessage"
            />
        </template>
        
    </GroupCardLayout>
</template>

<script>
import { mapGetters } from "vuex"

import SmallUser from   '@/Components/Reuseables/SmallUser.vue';
import MessageCard from "@/Components/Chat/reuseables/MessageCard.vue"
import * as ns from '@/Store/module_namespaces.js'
import GroupCardLayout from '@/Layouts/GroupCardLayout.vue'

export default {
    props: [ 'group_id', ],

    components: { SmallUser, MessageCard, GroupCardLayout, },

    data(){
        return {
            txtOneParticipant: "Only you.",
        }
    },

    computed:{
        ...mapGetters({ 
            user: "user",
        }),

        group(){ return this.$store.getters[ns.groupModule(this.group_id, 'state')] },

        lastMessage(){ return this.$store.getters[ns.groupModule(this.group_id, 'last_message')] },

        atLeastTwoParticpants(){ return Object.keys(this.group.participants).length >= 2 },

        seen(){ return this.$store.getters[ns.groupModule(this.group_id, 'seen')] },

        numUnseenMsges(){ return this.$store.getters[this.group_id, 'numUnseenMsges'] },
    },


    created(){

    },

    methods: {
        hasLastMessage(msg){ return msg.hasOwnProperty('id') ? true : false },
    },
}
</script>