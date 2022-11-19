<template>
    <div :class="['grid gap-2 items-center mr-1', canRemoveAnybody ? 'grid-cols-2' : 'grid-cols-4']" >
        <div :class="['p-2 sm:p-1', canRemoveAnybody ? '' : 'col-span-3']">
            <SmallUser :user="participant"  /> 
        </div>

        <div class="flex gap-2 h-full">
            <RoleBlock 
                v-if="!pendingDeleteAccept"
                :canPromoteDemote="canPromoteDemote" 
                :participant="participant" 
                :changeableRoles="permissions.change_role[participant.pivot.participant_role]"
                @roleCard="pendingRoleEvent"
            />

            <div v-if="!pendingRole" class="ml-auto my-auto">
                <div v-if="!pendingDeleteAccept">
                    <DeleteIcon v-if="canRemove" @click="pendingDeleteAccept=true" class="h-10 stroke-gray-500 hover:stroke-red-400 fill-transparent cursor-pointer" />
                </div>
                
                <div v-else class="w-full h-full pop-card-color border border-red-500">
                    <ConfirmAction>
                        <template #question>
                            <p class="">Are you sure you wish to remove this user from chat?</p>
                        </template>

                        <template #accept>
                            <AcceptIcon @click="removeParticipant(participant.id)" class="h-14 fill-gray-500 py-2 hover:fill-red-500" />
                        </template>

                        <template #decline>
                            <DeclineIcon @click="pendingDeleteAccept=false" class="h-14 fill-gray-500 py-2 hover:fill-gray-400" />
                        </template>
                    </ConfirmAction>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import RoleBlock from '@/Components/Chat/ChatWindow/Body/Config/Participants/RoleBlock.vue'
import SmallUser from '@/Components/Reuseables/SmallUser.vue';
import DeleteIcon from "@/Components/Reuseables/Icons/DeleteIcon.vue"
import ConfirmAction from '@/Layouts/Inputs/ConfirmAction.vue'
import DeclineIcon from "@/Components/Reuseables/Icons/DeclineIcon.vue"
import AcceptIcon from "@/Components/Reuseables/Icons/AcceptIcon.vue"

export default{
    inject: ['group_id'],

    props: [ 'participant', 'canRemoveAnybody', 'canPromoteDemote', 'permissions', 'canRemove' ],

    components: {RoleBlock, SmallUser, DeleteIcon, ConfirmAction, AcceptIcon, DeclineIcon, },

    data(){
        return {
            pendingDeleteAccept: false,
            pendingRole: false,
        }
    },

    methods: {
        removeParticipant(id){
            this.$store.dispatch(ns.groupModule(this.group_id, 'removeParticipant'), id)
        },

        /**
         * This emmited event is from grandchild :/ 
         * 
         * In order to make it fill remaining space...
         * 
         * @param {Boolean} bool - has user selected one of possible roles to be changed 
         */
        pendingRoleEvent(bool){
            this.pendingRole = bool
        }

    },
}
</script>

<style scoped>

</style>