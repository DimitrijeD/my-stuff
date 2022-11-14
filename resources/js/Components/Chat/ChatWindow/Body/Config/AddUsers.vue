<template>
    <DefaultCardLayout>
        <template #body>
            <DoubleUserSelectList 
                :searchInput="{
                    actions: actions,
                    exclude: excludeUsersFromSearch, 
                    placeholder: 'Find users', 
                    cls: 'dark:bg-darker-300 dark:border-darker-400',
                }"
                @toggled="toggled"
                :flushSelected="flushSelected"
            />
        </template>

        <template #footer>
            <button :class="['py-2 mt-2 def-btn', anySelected 
                ? 'text-white bg-blue-500 hover:bg-blue-600 dark:text-green-300 dark:bg-darker-400 dark:hover:text-green-500' 
                : 'cursor-not-allowed text-gray-600 bg-gray-200 dark:text-gray-400 dark:bg-darker-400']" 
                @click="addParticipants()"
            >
                {{ btnTxt }}
            </button>
        </template>

    </DefaultCardLayout>
</template>

<script>
import { mapGetters } from 'vuex'
import SearchInput from '@/Components/Chat/reuseables/SearchInput.vue'
import SmallUser from   '@/Components/Reuseables/SmallUser.vue';
import * as ns from '@/Store/module_namespaces.js'
import DoubleScrollContentCardLayout from '@/Layouts/DoubleScrollContentCardLayout.vue'
import LoadingCyrcle from '@/Components/Reuseables/LoadingCyrcle.vue'
import DoubleUserSelectList from '@/Components/Chat/reuseables/DoubleUserSelectList.vue'
import DefaultCardLayout from '@/Layouts/DefaultCardLayout.vue'

export default {
    props: [ 'group', 'permissions' ],

    components: { DefaultCardLayout, DoubleUserSelectList, LoadingCyrcle, SearchInput, SmallUser, DoubleScrollContentCardLayout, },

    data() {
        return {
            addedUsersIds: [],
            actions: {
                api: ns.users('searchForAddUsersInApi'),
                store: ns.users('searchForAddUsersInStore')
            },

            flushSelected: 0,
            
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
        
        toggled(users){
            this.addedUsersIds = users
        },

        addParticipants(){
            if(!this.addedUsersIds.length) return

            this.$store.dispatch(ns.groupModule(this.group.id, 'addParticipants'), {
                addedUsersIds: this.addedUsersIds,
                massAssignRolesTo: this.group.model_type == "PUBLIC_CLOSED" ? "LISTENER" : "PARTICIPANT"
            }).then(() =>{
                this.addedUsersIds = []
                this.$store.dispatch(ns.users('clearAddedUsersFromList'), this.excludeUsersFromSearch)
                this.flushSelected++
            })
        },

    },
}
</script>

<style scoped>
    .info-txt { @apply m-auto italic font-light text-gray-600 dark:text-gray-400;}
</style>