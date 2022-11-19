<template>
    <div v-if="canPromoteDemote" class="grow h-full my-auto ">
        <ChangeUserRole
            :participant="participant"
            :changeableRoles="changeableRoles"
            @roleCard="roleCard"
        />
    </div>
    <div v-else class="px-4 py-1.5 my-auto">
        {{ getParticipantRoleForHumans(participant) }} 
    </div>
</template>

<script>
import ChangeUserRole from '@/Components/Chat/ChatWindow/Body/Config/Participants/ChangeUserRole.vue'

export default {
    props: [ 'canPromoteDemote', 'participant', 'changeableRoles', ],

    components: { ChangeUserRole, },

    methods: {
        getParticipantRoleForHumans(participant){
            return participant.pivot.participant_role.toLowerCase()
        },

        roleCard(data){
            this.$emit('roleCard', data)
        }
    }
}

</script>