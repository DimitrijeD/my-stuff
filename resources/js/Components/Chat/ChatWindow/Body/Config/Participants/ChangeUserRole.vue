<template>
    <div v-if="pendingRoleChangeAccept" class="h-20">
        <div class="h-full flex place-items-center gap-2 p-0.5 text-center grid grid-cols-4 bg-gradient-to-b border box-border 
            from-yellow-400 to-yellow-200 
            dark:from-darker-500 dark:to-darker-300 dark:border-darker-500 ">
            <span class="col-span-2">Make user <span class="text-white dark:text-blue-400">{{ this.chosenNewRole.toLowerCase() }} ?</span></span>

            <button
                class="text-green-500 hover:text-green-600 "
                @click="confirmedChangeRole"
            >Yes</button>

            <button
                class="text-gray-700 dark:text-gray-300"
                @click="declinedChangeRole"
            >No</button>
        </div>
    </div>
    <div v-else class="h-full">
        <select 
            @change="roleUpdate($event)" 
            class="font-light w-full h-full px-2 py-1 focus:outline-none border
            bg-transparent border-gray-200/50 dark:border-darker-400/50"
        >
            <option :class="['bg-transparent', roleColors[getPrticipantRole(participant)]]" selected>
                {{ getParticipantRoleForHumans(participant) }} 
            </option>

            <option 
                v-for="toRole in changeableRoles"
                :value="toRole"
                class="bg-gray-300 dark:bg-darker-400"
            > {{ toRole.toLowerCase() }} </option>
        </select>
    </div>
</template>

<script>
import * as ns from '@/Store/module_namespaces.js'

export default{
    props:[ 'participant', 'roleColors',  'changeableRoles', 'group_id' ],

    data(){
        return {
            pendingRoleChangeAccept: false,
            chosenNewRole: null,
            gm_ns: ns.groupModule(this.group_id),
        }
    },

    methods: {
        roleUpdate(e){
            this.chosenNewRole = e.target.value
            this.pendingRoleChangeAccept = true
        },

        confirmedChangeRole(){
            this.$store.dispatch(this.gm_ns + '/changeParticipantRole', {
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

        getParticipantRoleForHumans(participant){
            return participant.pivot.participant_role.toLowerCase()
        },

        getPrticipantRole(participant){
            return participant.pivot.participant_role
        },
    },

}

</script>

<style scoped>

</style>