<template>
    <PendingAcceptGroupCardLayout :name="name">
        <template #inviter>
            <SmallUser 
                :user="getInviter()" 
                :imgCls="'w-12 h-12'" 
                :layoutCls="'pl-1'"
            />
        </template>

        <template #accept>
            <button 
                @click="responseToInv(true)"
                class="btn 
                    border-gray-300 text-green-700 hover:border-green-500
                    dark:border-darker-400 dark:text-green-500 dark:hover:border-green-500"
            >Join</button>
        </template>

        <template #decline>
            <button 
                @click="responseToInv(false)"
                class="btn 
                    border-gray-300 hover:border-gray-500 text-gray-600 hover:text-gray-800
                    dark:border-darker-400 dark:text-gray-500 dark:hover:border-gray-500 dark:hover:text-gray-400"
            >Decline</button>
        </template>
    </PendingAcceptGroupCardLayout>
</template>

<script>
import { mapGetters } from "vuex"
import PendingAcceptGroupCardLayout from '@/Layouts/Chat/PendingAcceptGroupCardLayout.vue'
import SmallUser from '@/Components/Reuseables/SmallUser.vue';

export default{
    props: ['group_id'],

    components: { PendingAcceptGroupCardLayout, SmallUser, },

    data(){
        return {

        }
    },

    computed: {
        ...mapGetters({ 
            user: "user",
        }),

        name(){
            return this.$store.getters[ns.groupModule(this.group_id, 'name')] 
        },
    },

    methods: {
        getInviter(){
            return this.$store.getters[ns.groupModule(this.group_id, 'participantsM/myInviter')] 
        },

        responseToInv(bool){
            this.$store.dispatch(ns.groupModule(this.group_id, 'participantsM/responseToInvitationToGroup'), bool)
        },

    }
}
</script>

<style scoped>
.btn {
    @apply border rounded m-2 def-trans;
}
</style>