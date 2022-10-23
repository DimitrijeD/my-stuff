<template>
    <DoubleScrollContentCardLayout class="select-none ">
        <template #header >
            <search-input 
                :actions="input.actions"
                :exclude="excludeUsersFromSearch"
                :placeholder="input.placeholder"
            />
        </template>

        <template #content-left >
            <ul > 
                <li v-for="(id, index) in listUsers" :key="index" >
                    <small-user 
                        v-if="!addedUsersIds.includes(id)"
                        :user="getUser(id)"
                        @click.native="add(id)"
                        class="select-user"
                    /> 
                </li>
            </ul>
        </template>

        <template #content-right>
            <p v-if="addedUsersIds.length == 0" class="text-center italic font-extralight text-gray-500">
                Selected users will appear in this list
                <br><br>
                After adding user, you can remove him by clicking on him
            </p>
            <ul class=""> 
                <li v-for="(id, index) in addedUsersIds" :key="index">
                    <small-user 
                        :user="getUser(id)"
                        @click.native="remove(id)"
                        class="select-user dark:hover:text-green-500"
                    />
                </li>
            </ul>
        </template>

        <template #footer>
            <button :class="['py-2 mt-2', anySelected ? 'not-disabled-btn' : '' , !anySelected ? 'disabled-btn' : '', ]" @click="addParticipants()">
                {{ btnTxt }}
            </button>
        </template>
    </DoubleScrollContentCardLayout>
</template>

<script>
import { mapGetters } from 'vuex'
import SearchInput from '@/Components/Chat/reuseables/SearchInput.vue'
import SmallUser from   '@/Components/Reuseables/SmallUser.vue';
import * as ns from '@/Store/module_namespaces.js'
import DoubleScrollContentCardLayout from '@/Layouts/DoubleScrollContentCardLayout.vue'

export default {
    props: [
        'group', 'permissions'
    ],

    components: {
        'search-input': SearchInput,
        'small-user': SmallUser,
        DoubleScrollContentCardLayout,
    },

    data() {
        return {
            addedUsersIds: [],
            input: {
                actions: {
                    api: ns.users() + '/searchForAddUsersInApi',
                    store: ns.users() + '/searchForAddUsersInStore'
                },
                placeholder: "Find users to add",
            },

            gm_ns: ns.groupModule(this.group.id),
        }
    },

    computed: {
        ...mapGetters({ 
            user: "user",
        }),

        listUsers(){ return this.$store.getters[ns.users() + '/getFilterForAddUsers'] },

        anySelected() {return this.addedUsersIds.length ? true : false}, 

        btnTxt(){
            let numSelected = this.addedUsersIds.length
            if(numSelected == 0)                    return 'Select at least one user'
            if(numSelected == 1)                    return 'Add selected user'
            if(numSelected > 1 && numSelected <= 4) return 'Add selected users'
            if(numSelected > 4)                     return `Add all ${numSelected} selected users`
        },

        excludeUsersFromSearch(){ return Object.keys(this.$store.getters[this.gm_ns + '/participants']) },
    },

    methods: 
    {
        getUser(id){
            return this.$store.getters[ns.users() + '/getById'](id)
        },
        
        add(id){
            if(!this.addedUsersIds.includes(id)) this.addedUsersIds.push(id)
        },

        remove(id){
            if(this.addedUsersIds.includes(id)) this.addedUsersIds.splice(this.addedUsersIds.indexOf(id), 1)
        },

        addParticipants(){
            if(!this.addedUsersIds.length) return

            this.$store.dispatch(this.gm_ns + '/addParticipants', {
                addedUsersIds: this.addedUsersIds,
                massAssignRolesTo: this.group.model_type == "PUBLIC_CLOSED" ? "LISTENER" : "PARTICIPANT"
            }).then(() =>{
                this.addedUsersIds = []
            })
        },
    },
}
</script>

<style scoped>
.select-user {
    @apply py-1 hover:bg-green-300 dark:hover:bg-darker-50;
}
</style>