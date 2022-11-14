<template>
    <div class="space-y-4 dark:text-gray-300">
        <div v-if="canChangeName" class="flex place-items-center gap-2" >
            <label class="select-none">Change group name</label>

            <input type="text" class="small-input dark:bg-darker-400 dark:border-darker-500 flex-1" v-model="newGroupName" :placeholder="group.name ? group.name : 'Group name is empty'">

            <button @click="changeGroupName" :class="[ 'p-2 rounded-xl border border-darker-500', validateChangeName ? 'text-green-600 border-green-600 ' : 'cursor-not-allowed text-gray-600 dark:text-gray-500', ]">
                Save
            </button>
        </div>

        <div v-if="canChangeGroupType" class="h-16 ">
            <div v-if="pendingGroupTypeChangeAccept" 
                class="h-full flex place-items-center gap-2 p-0.5 text-center bg-gradient-to-b border 
                from-yellow-400 to-yellow-200 
                dark:from-darker-500 dark:to-darker-300 dark:border-darker-500 "
            >
                <span class="p-2">Change group type to <span class="text-white dark:text-blue-400">{{ this.chosenNewType }} ?</span></span>

                <div class="ml-auto">
                    <button class="text-green-500 hover:text-green-600 p-4" @click="confirmedChangeType()">Yes</button>

                    <button class="text-gray-700 dark:text-gray-300 p-4" @click="declinedChangeType()">No</button>
                </div>
            </div>

            <div v-else class="h-full flex place-items-center gap-2">
                <p class="min-w-fit">Change group type</p>
                <DropDownInput
                    @selected="selectedNewType"
                    :current="currentModelType"
                    :items="getAvailableGroupModelTypesForHumans()"
                    class="w-full h-full bg-gray-200 dark:bg-darker-400 rounded-lg"
                    :headCls="'p-4 h-full flex place-items-center border border-gray-400 dark:border-darker-300'" 
                    :arrowCls="'w-6 h-6 fill-gray-300 stroke-gray-500'" 
                    :itemsWrapCls="'z-50 py-2 bg-gray-200 dark:bg-darker-400 border-2 dark:border-darker-300'" 
                    :itemCls="'py-2 px-4 mx-1 rounded-lg hover:dark:bg-darker-300'"
                />
            </div>
        </div>

        <AreYouSureLayout class="py-10 border border-red-400 rounded">
            <template #button-as-wrapper>
                <button class="danger-btn ">
                    Leave group
                </button>
            </template>
            <template #question>
                <p>
                    Are you sure you wish to leave this chat?
                </p>
            </template>

            <template #yes>
                <AcceptIcon @click="leaveGroup()" class="h-14 fill-gray-500 py-2 hover:fill-red-500" />
            </template>

            <template #no>
                <DeclineIcon class="h-14 fill-gray-500 py-2 hover:fill-gray-400" />
            </template>
        </AreYouSureLayout>
    </div>
</template>

<script>
import * as ns from '@/Store/module_namespaces.js'
import DeclineIcon from "@/Components/Reuseables/Icons/DeclineIcon.vue"
import AcceptIcon from "@/Components/Reuseables/Icons/AcceptIcon.vue"
import AreYouSureLayout from '@/Layouts/AreYouSureLayout.vue'
import DropDownInput from '@/Components/Reuseables/DropDownInput.vue'
import * as StringUtil from '@/UtilityFunctions/str.js'

export default {
    props: [ 'group', 'permissions' ],

    components: { DeclineIcon, AcceptIcon, AreYouSureLayout, DropDownInput },
    
    data() {
        return {
            user: this.$store.state.auth.user,
            newGroupName: this.group.name,

            pendingGroupTypeChangeAccept: false,
            chosenNewType: '',
            chosenNewTypeRaw: '',
        }
    },

    computed: {
        validateChangeName(){
            return (this.newGroupName == this.group.name) || (this.group.name === null && this.newGroupName === '') 
                ? false
                : true
        },

        canChangeName(){
            return this.permissions.change_group_name ? true : false
        },

        canChangeGroupType(){
            return this.permissions.change_group_type ? true : false
        },

        currentModelType(){
            return StringUtil.forHumans(this.group.model_type)
        },

        groupTypes(){
            return this.$store.getters[ ns.chat_rules('StateGroupTypes') ]
        },
    },

    methods: {
        leaveGroup() { this.$store.dispatch(ns.groupModule(this.group.id, 'leaveGroup')) },

        changeGroupName(){
            if(!this.validateChangeName) return

            this.$store.dispatch(ns.groupModule(this.group.id, 'changeGroupName'), {
                group_id: this.group.id,
                new_name: this.newGroupName
            })
        },

        /**
         * Returns array of group model types without current one
         */
        getAvailableGroupModelTypesForHumans(){
            let types = {}

            for(let i in this.groupTypes){
                if(this.groupTypes[i] != this.group.model_type)
                    types[this.groupTypes[i]] = StringUtil.forHumans(this.groupTypes[i])
            }

            return types
        },

        selectedNewType(type){
            this.pendingGroupTypeChangeAccept = true

            this.chosenNewType = type.forHumans
            this.chosenNewTypeRaw = type.raw
        },

        confirmedChangeType(){
            this.$store.dispatch(ns.groupModule(this.group.id, 'changeGroupType'), {
                group_id: this.group.id,
                model_type: this.chosenNewTypeRaw
            }).then(()=>{
                this.pendingGroupTypeChangeAccept = false
            }).catch(()=>{
                this.pendingGroupTypeChangeAccept = false
            })
        },

        declinedChangeType(){
            this.pendingGroupTypeChangeAccept = false
        }

    }
}
</script>