<template>
    <div>
        <div v-if="pendingRoleChangeAccept">
            <div class="text-center grid grid-cols-4 font-light bg-gradient-to-b 
                from-yellow-400 to-yellow-200 
                dark:from-darker-800 dark:to-darker-600">
                <span class="col-span-2">Make user <span class="text-white dark:text-blue-400">{{ this.chosenNewRole.toLowerCase() }} ?</span></span>

                <button
                    class="text-green-500 hover:text-green-600 dark:text-green-500 dark:hover:text-green-400"
                    @click="confirmedChangeRole"
                >Yes</button>

                <button
                    class="text-gray-700 dark:text-gray-300"
                    @click="declinedChangeRole"
                >No</button>
            </div>
        </div>
        <div v-else>
            <select 
                @change="roleUpdate($event)" 
                class="font-light w-full px-2 py-1 focus:outline-none 
                text-gray-700
                dark:bg-darker-300 dark:text-gray-400">
                <option class="block w-full" selected>{{ participant.pivot.participant_role.toLowerCase() }}</option>
                <option 
                    v-for="toRole in changeableRoles"
                    :value="toRole"
                    class="block w-full"
                > {{ toRole.toLowerCase() }} </option>
            </select>
        </div>
    </div>
</template>

<script>
import * as ns from '@/Store/module_namespaces.js'

export default{
    props:[ 'participant_id', 'changeableRoles', 'group_id' ],

    data(){
        return {
            pendingRoleChangeAccept: false,
            chosenNewRole: null,
            gm_ns: ns.groupModule(this.group_id),
        }
    },

    computed: {
        participant(){ return this.$store.getters[this.gm_ns + '/getParticipant'](this.participant_id) },
        
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
        }
    },

}

</script>