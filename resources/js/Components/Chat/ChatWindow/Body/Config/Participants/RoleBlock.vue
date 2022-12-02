<template>
    <div v-if="canPromoteDemote && participant.pivot.accepted" class="grow h-full my-auto ">
        <ChangeUserRole
            :participant="participant"
            :changeableRoles="changeableRoles"
            @roleCard="roleCard"
        />
    </div>
    <div v-else-if="!participant.pivot.accepted" class="px-4 py-1.5 my-auto text-yellow-500">
        pending
    </div>
    <div v-else class="px-4 py-1.5 my-auto">
        {{ getParticipantRoleForHumans() }} 
    </div>
</template>

<script>
import ChangeUserRole from '@/Components/Chat/ChatWindow/Body/Config/Participants/ChangeUserRole.vue'

export default {
    props: [ 'canPromoteDemote', 'participant', 'changeableRoles', ],

    components: { ChangeUserRole, },

    methods: {
        getParticipantRoleForHumans(){
            return this.participant.pivot.participant_role.toLowerCase()
        },

        roleCard(data){
            this.$emit('roleCard', data)
        }
    }
}

</script>