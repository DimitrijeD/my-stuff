<template>
    <div class="h-full">
        <div v-show="pendingRoleChangeAccept" class="h-full flex flex-rows place-items-center pl-1 py-1 pop-card-color gap-0.5">
            <p class="text-center">
                Make user <span class="break text-white dark:text-blue-400">{{ this.chosenNewRole?.toLowerCase() }}</span>?
            </p>

            <AcceptIcon @click="confirmedChangeRole()" class="h-14 py-2 fill-gray-500 dark:hover:fill-green-800" />
            <DeclineIcon @click="declinedChangeRole()" class="h-14 py-2 fill-gray-500" />
        </div>

        <div v-show="!pendingRoleChangeAccept" class="h-full flex place-items-center">
            <DropDownInput 
                @selected="roleUpdate"
                :current="currentRole"
                :items="getAllRolesForHumans()"
                class="w-full bg-gray-200 dark:bg-darker-400 rounded-t-lg"
                :headCls="'p-4 border border-gray-400 dark:border-darker-300'" 
                :arrowCls="'w-6 h-6 fill-gray-300 stroke-gray-500'" 
                :itemsWrapCls="'z-50 py-2 bg-gray-200 dark:bg-darker-400 border-2 dark:border-darker-300'" 
                :itemCls="'py-2 px-4 mx-1 rounded-lg hover:dark:bg-darker-300'"
            />
        </div>
    </div>
</template>

<script>
import DropDownInput from '@/Components/Reuseables/DropDownInput.vue'
import * as StringUtil from '@/UtilityFunctions/str.js'
import AreYouSureLayout from '@/Layouts/AreYouSureLayout.vue'
import DeclineIcon from "@/Components/Reuseables/Icons/DeclineIcon.vue"
import AcceptIcon from "@/Components/Reuseables/Icons/AcceptIcon.vue"

export default{
    inject: ['group_id'],

    props:[ 'participant', 'changeableRoles', ],

    components: { DropDownInput, AreYouSureLayout, AcceptIcon, DeclineIcon }, 

    data(){
        return {
            pendingRoleChangeAccept: false,
            chosenNewRole: null,
        }
    },

    computed: {
        currentRole(){
            return StringUtil.forHumans(this.participant.pivot.participant_role)
        },
    },

    methods: {
        roleUpdate(role){
            this.chosenNewRole = role.raw
            this.pendingRoleChangeAccept = true
            this.$emit('roleCard', this.pendingRoleChangeAccept)
        },

        confirmedChangeRole(){
            this.$store.dispatch(ns.groupModule(this.group_id, 'changeParticipantRole'), {
                target_user_id: this.participant.id,
                to_role: this.chosenNewRole,
            })
            
            this.resetVars()
        },

        declinedChangeRole(){ this.resetVars() },

        resetVars(){
            this.pendingRoleChangeAccept = false
            this.chosenNewRole = null
            this.$emit('roleCard', this.pendingRoleChangeAccept)
        },

        getPrticipantRole(){
            return this.participant.pivot.participant_role
        },

        getAllRolesForHumans(){
            let roles = {}

            for(let i in this.changeableRoles){
                roles[this.changeableRoles[i]] = StringUtil.forHumans(this.changeableRoles[i])
            }

            return roles
        }
    },

}

</script>

<style scoped>

</style>