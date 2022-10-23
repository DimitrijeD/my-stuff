<template>
    <DefaultCardLayout class="space-y-2">
        <template #header >
            <input 
                class="small-input"
                type="text" 
                placeholder="todo input for filtering these users"
            > 
        </template>

        <template #content>
            <div class="grid grid-cols-12 items-center" v-for="participant in participants" :key="participant.id">
                <div class="col-span-6">
                    <SmallUser :user="participant" :userNameCls="'text-gray-700 dark:text-gray-400'" /> 
                </div>

                <div class="col-span-5">
                    <div v-if="canPromoteDemote(participant)">
                        <ChangeUserRole
                            :participant_id="participant.id"
                            :changeableRoles="permissions[change_role][getPrticipantRole(participant)]"
                            :group_id="group.id"
                        />
                    </div>
                    <div v-else class="text-gray-700 dark:text-gray-400 font-light px-4 py-1.5">
                        {{ getParticipantRoleForHumans(participant) }}
                    </div>
                </div>

                <DeleteIcon v-if="canRemove(participant)" @click="removeParticipant(participant.id)"
                    class="stroke-red-300 hover:stroke-red-400 fill-transparent h-8 cursor-pointer"
                />

            </div>
        </template>
    </DefaultCardLayout>

</template>

<script>
import DeleteIcon from "@/Components/Reuseables/Icons/DeleteIcon.vue"
import SmallUser from   '@/Components/Reuseables/SmallUser.vue';
import ChangeUserRole from '@/Components/Chat/ChatWindow/Body/Config/Participants/ChangeUserRole.vue'
import * as ns from '@/Store/module_namespaces.js'
import DefaultCardLayout from '@/Layouts/DefaultCardLayout.vue';
import AreYouSureLayout from '@/Layouts/AreYouSureLayout.vue'

export default {
    props: [ 'group', 'chatRole', 'permissions', 'roles' ],

    components: {
        SmallUser,
        ChangeUserRole,
        DefaultCardLayout,
        DeleteIcon,
        AreYouSureLayout,
    },

    data() {
        return {
            user: this.$store.state.auth.user,
            change_role: 'change_role',
            remove: 'remove',
            gm_ns:  ns.groupModule(this.group.id),
        }
    },

    computed: {
        participants(){ return this.sortParticipantsByRoleHierarchy(this.$store.getters[this.gm_ns + '/participants']) },

    },

    methods: 
    {
        removeParticipant(id){
            this.$store.dispatch(this.gm_ns + '/removeParticipant', id).then(()=>{
                // show success message that user was successfully added to group
            }).catch(e => {
                //
            })
        },

        canRemove(participant){
            if(!this.actionRule_ParticipantNotSelf(participant.id)) return false

            if(!this.permissions.remove.includes(this.getPrticipantRole(participant))) return false 
            
            return true
        },

        canPromoteDemote(participant){
            if(!this.actionRule_ParticipantNotSelf(participant.id, this.user.id)) return false

            let fromRoles = Object.keys(this.permissions.change_role)

            if(!this.actionRule_RuleTableNotEmpty(fromRoles)) return false

            if(!fromRoles.includes(this.getPrticipantRole(participant))) return false // participant is not among roles which can be changed under these conditions

            return true
        }, 

        actionRule_ParticipantNotSelf(participant_id){
            return participant_id == this.user.id ? false : true
        },

        actionRule_RuleTableNotEmpty(permissionKeys){
            return permissionKeys.length == 0 ? false : true
        },

        getPrticipantRole(participant){
            return participant.pivot.participant_role
        },

        getParticipantRoleForHumans(participant){
            return participant.pivot.participant_role.toLowerCase()
        },

        sortParticipantsByRoleHierarchy(participants){
            let groupedByRole = {}
            let sortedByRole = []

            for(let i in this.roles){
                groupedByRole[this.roles[i]] = []
            }

            for(let i in participants){
                groupedByRole[participants[i].pivot.participant_role].push(participants[i]) 
            }

            for(let i in groupedByRole){
                sortedByRole = sortedByRole.concat(groupedByRole[i]);
            }

            return sortedByRole
        }

    }
}
</script>