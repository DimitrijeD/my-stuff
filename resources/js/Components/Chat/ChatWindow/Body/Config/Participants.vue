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
            <div class="overflow-hidden grid grid-cols-2 gap-2 items-center" v-for="participant in participants" :key="participant.id">
                <div class="p-2 sm:p-1">
                    <SmallUser :user="participant"  /> 
                </div>

                <div class="flex items-center gap-2 h-full">
                    <RoleBlock 
                        :canPromoteDemote="canPromoteDemote(participant)" 
                        :participant="participant" 
                        :permissions="permissions" 
                        :group_id="group.id"
                    />

                    <div class="ml-auto ">
                        <DeleteIcon v-if="canRemove(participant)" @click="removeParticipant(participant.id)"
                            class="mr-2 stroke-gray-500 hover:stroke-red-400 fill-transparent h-8 cursor-pointer"
                        />
                    </div>
                </div>
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
import RoleBlock from '@/Components/Chat/ChatWindow/Body/Config/Participants/RoleBlock.vue'

export default {
    props: [ 'group', 'chatRole', 'permissions', 'roles' ],

    components: { SmallUser, ChangeUserRole, DefaultCardLayout, DeleteIcon, AreYouSureLayout, RoleBlock, },

    data() {
        return {
            user: this.$store.state.auth.user,
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