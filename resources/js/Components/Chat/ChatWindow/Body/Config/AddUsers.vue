<template>
    <DoubleScrollContentCardLayout class="select-none ">
        <template #header >
            <SearchInput 
                :actions="input.actions"
                :exclude="excludeUsersFromSearch"
                :placeholder="input.placeholder"
            />
        </template>

        <template #content-left >
            <ul> 
                <li v-for="(id, index) in listUsers" :key="index" >
                    <small-user 
                        v-if="!addedUsersIds.includes(id)"
                        :user="getUser(id)"
                        @click.native="add(id)"
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
            <ul> 
                <li v-for="(id, index) in addedUsersIds" :key="index">
                    <SmallUser 
                        :user="getUser(id)"
                        @click.native="remove(id)"
                        class="dark:hover:text-green-500"
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
    props: [ 'group', 'permissions' ],

    components: { SearchInput, SmallUser, DoubleScrollContentCardLayout, },

    data() {
        return {
            addedUsersIds: [],
            input: {
                actions: {
                    api: ns.users('searchForAddUsersInApi'),
                    store: ns.users('searchForAddUsersInStore')
                },
                placeholder: "Find users to add",
            },
            
        }
    },

    computed: {
        ...mapGetters({ 
            user: "user",
        }),

        listUsers(){ return this.$store.getters[ns.users('getFilterForAddUsers')] },

        anySelected(){ return this.addedUsersIds.length ? true : false }, 

        btnTxt(){
            let numSelected = this.addedUsersIds.length
            if(numSelected == 0)                    return 'Select at least one user'
            if(numSelected == 1)                    return 'Add selected user'
            if(numSelected > 1 && numSelected <= 4) return 'Add selected users'
            if(numSelected > 4)                     return `Add all ${numSelected} selected users`
        },

        excludeUsersFromSearch(){ 
            return Object.keys( this.group.participants )
         },
    },

    methods: {
        getUser(id){
            return this.$store.getters[ns.users('getById')](id)
        },
        
        add(id){
            if(!this.addedUsersIds.includes(id)) this.addedUsersIds.push(id)
        },

        remove(id){
            if(this.addedUsersIds.includes(id)) this.addedUsersIds.splice(this.addedUsersIds.indexOf(id), 1)
        },

        addParticipants(){
            if(!this.addedUsersIds.length) return

            this.$store.dispatch(ns.groupModule(this.group.id, 'addParticipants'), {
                addedUsersIds: this.addedUsersIds,
                massAssignRolesTo: this.group.model_type == "PUBLIC_CLOSED" ? "LISTENER" : "PARTICIPANT"
            }).then(() =>{
                this.addedUsersIds = []
                this.$store.dispatch(ns.users('clearAddedUsersFromList'), this.excludeUsersFromSearch)
            })
        },
    },
}
</script>