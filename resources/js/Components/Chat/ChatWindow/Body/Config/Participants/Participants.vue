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
            <ParticipantInList 
                v-for="participant in listedParticipants" :key="participant.id" 
                :participant="participant"
                :canRemoveAnybody="canRemoveAnybody"
                :canPromoteDemote="canPromoteDemote(participant)" 
                :permissions="permissions"
                :canRemove=canRemove(participant)
            />
        </template>
    </DefaultCardLayout>
</template>

<script>
import DeleteIcon from "@/Components/Reuseables/Icons/DeleteIcon.vue"
import SearchIcon from '@/Components/Reuseables/Icons/SearchIcon.vue'

import SmallUser from '@/Components/Reuseables/SmallUser.vue';
import ChangeUserRole from '@/Components/Chat/ChatWindow/Body/Config/Participants/ChangeUserRole.vue'
import RoleBlock from '@/Components/Chat/ChatWindow/Body/Config/Participants/RoleBlock.vue'
import ParticipantInList from '@/Components/Chat/ChatWindow/Body/Config/Participants/ParticipantInList.vue'
import DefaultCardLayout from '@/Layouts/DefaultCardLayout.vue';

import * as collection from '@/UtilityFunctions/collection.js'
import { mapGetters } from 'vuex'
import { fuzzyImmidiate }  from  '@/UtilityFunctions/fuzzyImmidiate.js'
import { RoleCan } from '@/Components/Chat/policies/RoleCan.js'

export default {
    inject: ['group_id'],

    props: ['permissions'],

    components: { SmallUser, ChangeUserRole, DefaultCardLayout, DeleteIcon, RoleBlock, SearchIcon, ParticipantInList },

    data() {
        return {
            searchStr: '',
            listedParticipants: [],
            roleCan: {},
        }
    },

    computed: {
        ...mapGetters({ 
            user: "user",
            roles: ns.chat_rules('StateRoles'),
        }),

        canRemoveAnybody(){
            return this.roleCan.removeAnybody()
        },

        chatRole(){ 
            return this.$store.getters[ ns.groupModule(this.group_id, 'getUserRole') ](this.user.id) 
        },

        participants(){ 
            return this.$store.getters[ ns.groupModule(this.group_id, 'participants') ]
        },
    },

    watch: {
        //Adding or removing participants from group requires them to be added or removed from list 
        participants: {
            handler: function () {
                this.searchParticipants()
            },
            deep: true,
        },

        permissions: {
            handler: function () {
                this.roleCan = new RoleCan(this.user, this.permissions)
            },
            deep: true,
        },
    }, 

    created(){
        this.roleCan = new RoleCan(this.user, this.permissions)

        this.listedParticipants = collection.sortParticipantsByRoleHierarchy(this.participants, this.roles)
    },

    methods: {
        canRemove(participant){
            return this.roleCan.remove(participant)
        },

        canPromoteDemote(participant){
            return this.roleCan.canPromoteDemote(participant)
        }, 

        searchParticipants(){
            this.listedParticipants = collection.sortParticipantsByRoleHierarchy(
                fuzzyImmidiate(
                    this.searchStr, 
                    this.participants, 
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

            for(let i in this.participants){
                if(this.regExpressionMatch(this.searchStr, this.participants[i].first_name) || this.regExpressionMatch(this.searchStr, this.participants[i].last_name)){
                    this.listedParticipants.push(this.participants[i])
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