<template>
    <div class="h-full">
        <div v-show="pendingRoleChangeAccept" 
            class="h-full flex place-items-center gap-2 p-0.5 text-center grid grid-cols-4 bg-gradient-to-b border 
            from-yellow-400 to-yellow-200 
            dark:from-darker-500 dark:to-darker-300 dark:border-darker-500 "
        >
            <span class="col-span-2">Make user <span class="text-white dark:text-blue-400">{{ this.chosenNewRole?.toLowerCase() }} ?</span></span>

            <button class="text-green-500 hover:text-green-600 " @click="confirmedChangeRole">Yes</button>

            <button class="text-gray-700 dark:text-gray-300" @click="declinedChangeRole">No</button>
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
import * as ns from '@/Store/module_namespaces.js'
import DropDownInput from '@/Components/Reuseables/DropDownInput.vue'
import * as StringUtil from '@/UtilityFunctions/str.js'

export default{
    props:[ 'participant', 'roleColors',  'changeableRoles', 'group_id' ],

    components: { DropDownInput,  }, 

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