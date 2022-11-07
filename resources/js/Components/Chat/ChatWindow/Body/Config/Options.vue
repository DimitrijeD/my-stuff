<template>
    <div class="space-y-4 ">
        <div v-if="canChangeName" class="flex place-items-center gap-2" >
            <label class="dark:text-gray-400">Change group name</label>

            <input type="text" class="small-input flex-1" v-model="newGroupName" :placeholder="group.name ? group.name : 'Group name is empty'">

            <button @click="changeGroupName" :class="[ 'p-2 rounded-xl border border-darker-500', validateChangeName ? 'text-green-600 border-green-600 ' : 'cursor-not-allowed text-gray-600 dark:text-gray-500', ]">
                Save
            </button>
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


export default {
    props: [ 'group', 'permissions' ],

    components: {
        DeclineIcon, AcceptIcon, AreYouSureLayout,
    },
    
    data() {
        return {
            user: this.$store.state.auth.user,
            awaitConfirmation: true,
            newGroupName: this.group.name,
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
        }
    },

    methods: 
    {
        leaveGroup() { this.$store.dispatch(ns.groupModule(this.group.id, 'leaveGroup')) },

        askConfirmation() { this.awaitConfirmation = false },

        declined() { this.awaitConfirmation = true },

        changeGroupName(){
            if(!this.validateChangeName) return

            this.$store.dispatch(ns.groupModule(this.group.id, 'changeGroupName'), {
                group_id: this.group.id,
                new_name: this.newGroupName
            })
        },

    }
}
</script>