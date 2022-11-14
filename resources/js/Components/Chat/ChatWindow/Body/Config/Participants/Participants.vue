<template>
    <DefaultCardLayout class="space-y-2">
        <template #header >
            <div class="w-full relative">
                <input
                    class="search-loop bg-gray-200 text-gray-700 dark:text-gray-300 dark:bg-darker-300 dark:border-darker-400"
                    placeholder="Find participants"
                    type="text"
                    v-model="searchStr"
                    @keyup="searchParticipants()"
                    autocomplete="none"
                >

                <SearchIcon class="w-8 h-full p-1 absolute left-1 top-0 fill-blue-500 dark:fill-blue-400 opacity-80" />
            </div>
        </template>

        <template #body>
            <div v-for="participant in listedParticipants" :key="participant.id" :class="['grid gap-2 items-center', canRemoveAnybody ? 'grid-cols-2' : 'grid-cols-4']" >
                <div :class="['p-2 sm:p-1', canRemoveAnybody ? '' : 'col-span-3']">
                    <SmallUser :user="participant"  /> 
                </div>

                <div class="flex gap-2 h-full">
                    <RoleBlock 
                        :canPromoteDemote="canPromoteDemote(participant)" 
                        :participant="participant" 
                        :permissions="permissions" 
                        :group_id="group.id"
                    />

                    <div class="ml-auto my-auto">
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
import SearchIcon from '@/Components/Reuseables/Icons/SearchIcon.vue'

import SmallUser from   '@/Components/Reuseables/SmallUser.vue';
import ChangeUserRole from '@/Components/Chat/ChatWindow/Body/Config/Participants/ChangeUserRole.vue'
import RoleBlock from '@/Components/Chat/ChatWindow/Body/Config/Participants/RoleBlock.vue'

import DefaultCardLayout from '@/Layouts/DefaultCardLayout.vue';
import AreYouSureLayout from '@/Layouts/AreYouSureLayout.vue'

import * as collection from '@/UtilityFunctions/collection.js'
import * as ns from '@/Store/module_namespaces.js'
import { mapGetters } from 'vuex'
import { fuzzyImmidiate }  from  '@/UtilityFunctions/fuzzyImmidiate.js'

export default {
    props: [ 'group', 'permissions'],

    components: { SmallUser, ChangeUserRole, DefaultCardLayout, DeleteIcon, AreYouSureLayout, RoleBlock, SearchIcon, },

    data() {
        return {
            searchStr: '',
            listedParticipants: []
        }
    },

    computed: {
        ...mapGetters({ 
            user: "user",
            roles: ns.chat_rules('StateRoles'),
        }),

        canRemoveAnybody(){
            return this.permissions.remove.length != 0 ? true : false
        },

        chatRole(){ return this.$store.getters[ ns.groupModule(this.group.id, 'getUserRole') ](this.user.id) },
    },

    created(){
        this.listedParticipants = collection.sortParticipantsByRoleHierarchy(this.group.participants, this.roles)
    },

    methods: {
        removeParticipant(id){
            this.$store.dispatch(ns.groupModule(this.group.id, 'removeParticipant'), id)
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


        searchParticipants(){
            this.listedParticipants = collection.sortParticipantsByRoleHierarchy(
                fuzzyImmidiate(
                    this.searchStr, 
                    this.group.participants, 
                    ['first_name', 'last_name']
                ),
                this.roles
            )
        },

        /**
         * -------------------------------------------------------------------------------------------------------------
         *                                  Alternative methods for searching participants                             -
         * -------------------------------------------------------------------------------------------------------------
         */

        /**
        searchParticipants(){
            this.listedParticipants = []

            for(let i in this.group.participants){
                if(this.regExpressionMatch(this.searchStr, this.group.participants[i].first_name) || this.regExpressionMatch(this.searchStr, this.group.participants[i].last_name)){
                    this.listedParticipants.push(this.group.participants[i])
                }
            }

            this.listedParticipants = this.sortParticipantsByRoleHierarchy(this.listedParticipants)
        },

        regExpressionMatch(find, text){
            let regex = new RegExp(find, 'i');
            return text.match(regex)
        }


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
        },

        */
        /**
         * -------------------------------------------------------------------------------------------------------------
         *                                                                                                             -
         * -------------------------------------------------------------------------------------------------------------
         */

    }
}
</script>