<template>
    <div v-if="canPromoteDemote" class="grow h-full">
        <ChangeUserRole
            :participant="participant"
            :changeableRoles="permissions.change_role[getPrticipantRole(participant)]"
            :group_id="group_id"
            :roleColors="roleColors"
        />
    </div>
    <div v-else :class="['px-4 py-1.5', roleColors[getPrticipantRole(participant)]]">
        {{ getParticipantRoleForHumans(participant) }} 
    </div>
</template>

<script>
import ChangeUserRole from '@/Components/Chat/ChatWindow/Body/Config/Participants/ChangeUserRole.vue'

export default {
    props: [ 'canPromoteDemote', 'participant', 'permissions', 'group_id'  ],

    components: { ChangeUserRole, },

    data() {
        return {
            roleColors: {
                CREATOR:     'text-blue-600 dark:text-blue-600',
                MODERATOR:   'text-blue-600 dark:text-blue-400',
                PARTICIPANT: 'text-blue-600 dark:text-gray-600',
                LISTENER:    'text-blue-600 dark:text-gray-400',
            }
        }
    },

    methods: {
        getPrticipantRole(participant){
            return participant.pivot.participant_role
        },

        getParticipantRoleForHumans(participant){
            return participant.pivot.participant_role.toLowerCase()
        },
    }
}

</script>