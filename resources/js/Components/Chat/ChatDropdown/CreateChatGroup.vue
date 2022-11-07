<template>
    <DoubleScrollContentCardLayout>
        <template #header >
            <div class="space-y-2 mb-2 relative">    
                <input
                    class="small-input"
                    placeholder="Name of new group chat"
                    type="text"
                    v-model="newChatGroup.name"
                >

                <select class="small-input" v-model="selected_model_type" >
                    <option 
                        v-for="(type, index) in getHumanReadableGroupTypes" 
                        v-bind:value="type.value" 
                        :key="index"
                    >
                        {{ type.text }}
                    </option>
                </select>

                <SearchInput
                    :actions="input.actions"
                    :exclude="newChatGroup.users_ids"
                    :placeholder="input.placeholder"
                    class="small-input" 
                    @typed="searchedAtLeastOnce()"
                    @awaitingApi="awaitingApi"
                />
            </div>
        </template>

        <template #content-left>
            <div class="relative h-full">
                <LoadingCyrcle 
                    :show="isAwaitingApi" 
                    :iconCls="'w-10 h-10 stroke-blue-500'" />

                <template v-for="(id, index) in users" :key="index" >
                    <SmallUser                     
                        v-if="!newChatGroup.users_ids.includes(id)"
                        :user="getUser(id)"
                        class="py-1 select-none"
                        @click.stop.prevent="add(id)"
                    /> 
                </template>

                <div v-if="users.length == 0" class="h-full flex">
                    <p v-if="!hasSearched" class="info-txt">Users will appear in this list.</p>
                    <p v-else              class="info-txt">Nothing found.</p>
                </div>
            </div>
        </template>

        <template #content-right>
            <SmallUser 
                v-for="(id, index) in newChatGroup.users_ids" 
                :key="index"
                :user="getUser(id)"
                :class="['py-1 select-none', isUserSelected(id) ? 'text-green-500' : '']"
                @click.stop.prevent="remove(id)"
            />
            <div v-if="newChatGroup.users_ids.length == 0" class="h-full flex">
                <p class="info-txt">
                    Selected users will appear in this list.
                </p>
            </div>
        </template>

        <template #footer>
            <p v-for="(error, index) in errors" class="def-error-text text-center p-2" :key="index">
                {{ error }}
            </p>

            <button :class="['py-2 mt-2', canCreate ? 'not-disabled-btn' : 'disabled-btn', ]" @click="createNewChatGroup()">
                Create chat group
            </button>
        </template>
    </DoubleScrollContentCardLayout>
</template>

<script>
import { mapGetters } from 'vuex';
import * as ns from '@/Store/module_namespaces.js'

import SmallUser from '@/Components/Reuseables/SmallUser.vue';
import SearchInput from '@/Components/Chat/reuseables/SearchInput.vue'
import DoubleScrollContentCardLayout from '@/Layouts/DoubleScrollContentCardLayout.vue'
import LoadingCyrcle from '@/Components/Reuseables/LoadingCyrcle.vue'

export default {
    components: { SearchInput, SmallUser, DoubleScrollContentCardLayout, LoadingCyrcle },

    data() {
        return {
            showCreateDropdown: false,
            newChatGroup: {
                name: '',
                users_ids: [],
                model_type: '',
            },
            errors: [],
            nothingFound: false,
            hasSearched: false,
            isAwaitingApi: false,
            hideLoadingAfterMS: 800,

            selected_model_type: 'PRIVATE',
            defaultNewGroupType: 'PRIVATE',

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
            groupTypes: ns.chat_rules('StateGroupTypes'),
        }),

        getHumanReadableGroupTypes(){
            let groupTypes = []

            for(let i in this.groupTypes){
                groupTypes.push({
                    text: this.capitalizeFirstLetter(this.groupTypes[i].replace("_", " ").toLowerCase()),
                    value: this.groupTypes[i]
                })
            }

            return groupTypes
        },

        users(){ 
            return this.$store.getters[ns.users('getFilterForAddUsers')] 
        },

        canCreate(){ return this.newChatGroup.users_ids.length }
    },

    methods:{        
        capitalizeFirstLetter(string) {
           return string.charAt(0).toUpperCase() + string.slice(1);
        },

        createNewChatGroup(){
            this.checkForErrors()

            if(this.errors.length > 0) return
            this.resolveGroupParams()

            this.$store.dispatch(ns.groupsManager('storeGroup'), this.newChatGroup).then(() => {
                this.resetComponentVars()
                this.$emit('closeDropdown')
            })
        },

        checkForErrors(){
            // reset errors before pushing new messages
            this.errors = []

            if(this.newChatGroup.users_ids.length === 0) this.errors.push('Select at least one user')

            return this.errors
        },

        resetComponentVars(){
            this.newChatGroup = {
                name: '',
                users_ids: [],
                model_type: '',
            }
            this.errors = []
            this.showCreateDropdown = false
        },

        resolveGroupParams(){
            this.newChatGroup.users_ids.push(this.user.id)
            this.resolveGroupType()
        },

        resolveGroupType(){
            if(this.selected_model_type == "PRIVATE" && this.newChatGroup.users_ids.length > 2){
                this.newChatGroup.model_type = "PROTECTED" 
                return
            }

            this.newChatGroup.model_type = this.selected_model_type         
                ? this.selected_model_type 
                : this.defaultNewGroupType

        },

        getUser(id) { return this.$store.getters[ns.users('getById')](id)},

        add(id){
            if(!this.newChatGroup.users_ids.includes(id)) this.newChatGroup.users_ids.push(id)
        },

        remove(id){
            if(this.newChatGroup.users_ids.includes(id)) this.newChatGroup.users_ids.splice(this.newChatGroup.users_ids.indexOf(id), 1)
        },

        isUserSelected(id){ return this.newChatGroup.users_ids.includes(id) },

        hideWindow(){ this.$emit('hideCreateChatGroupDropdown') },

        /**
         * After user typed in search user input, only once set 'hasSearched' to true indicating if 'no users found should be displayed or not' 
         */
        searchedAtLeastOnce(){
            this.hasSearched = true
        },

        awaitingApi(bool){
            if(bool){
                this.isAwaitingApi = true
            } else {
                setTimeout(()=> {
                    this.isAwaitingApi = false
                }, this.hideLoadingAfterMS)
            }
        },
    }

}
</script>

<style scoped>
.info-txt {
    @apply m-auto italic font-light text-gray-600 dark:text-gray-400;
}
</style>