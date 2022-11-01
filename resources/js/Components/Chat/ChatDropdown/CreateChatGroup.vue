<template>
    <DefaultCardLayout >
        <template #header >
            <div class="space-y-2 mb-2">    
                <!-- Group name -->
                <input
                    class="small-input"
                    placeholder="Name of new group chat"
                    type="text"
                    v-model="newChatGroup.name"
                >
                <!-- / -->

                <!-- Chose group type -->
                <select class="small-input" v-model="selected_model_type" >
                    <option 
                        v-for="(type, index) in getHumanReadableGroupTypes" 
                        v-bind:value="type.value" 
                        :key="index"
                        class=""
                    >
                        {{ type.text }}
                    </option>
                </select>
                <!-- / -->

                <!-- Input - Find users -->
                <SearchInput
                    :actions="input.actions"
                    :exclude="[]"
                    :placeholder="input.placeholder"
                    class="small-input" 
                />
                <!-- / -->
            </div>
        </template>

        <template #content>
            <!-- List of selectable user for new group -->
            <div v-for="(id, index) in users" :key="index" >
                <SmallUser 
                    :user="getUser(id)"
                    @click.native="selectOrDeseceltUser(id)"
                    :layoutCls="isUserSelected(id) 
                        ? 'selected-user' 
                        : 'not-selected-user'"
                    class="py-1"
                /> 
            </div>
            <!-- / -->

            <!-- List of validation errors -->
            <div v-for="(error, index) in errors" class="def-error-text" :key="index">
                {{ error }}
            </div>
            <!-- / -->
        </template>

        <template #footer>
            <button :class="['py-2 mt-2', canCreate ? 'not-disabled-btn' : '' , !canCreate ? 'disabled-btn' : '', ]" @click="createNewChatGroup()">
                Create chat group
            </button>
        </template>
    </DefaultCardLayout>

</template>

<script>
import { mapGetters } from 'vuex';
import * as ns from '@/Store/module_namespaces.js'

import SmallUser from '@/Components/Reuseables/SmallUser.vue';
import SearchInput from '@/Components/Chat/reuseables/SearchInput.vue'
import DefaultCardLayout from '@/Layouts/DefaultCardLayout.vue';

export default {
    components: { SearchInput, SmallUser, DefaultCardLayout,  },

    data() {
        return {
            showCreateDropdown: false,
            newChatGroup: {
                name: '',
                users_ids: [],
                model_type: '',
            },
            errors: [],

            selected_model_type: 'PRIVATE',
            defaultNewGroupType: 'PRIVATE',

            input: {
                actions: {
                    api: ns.users('searchForAddUsersInApi'),
                    store: ns.users('searchForAddUsersInApi')
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

        users(){ return this.$store.getters[ns.users('getFilterForAddUsers')] },

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

        selectOrDeseceltUser(id){
            this.newChatGroup.users_ids.includes(id) 
                ? this.newChatGroup.users_ids.splice(this.newChatGroup.users_ids.indexOf(id), 1)
                : this.newChatGroup.users_ids.push(id)
        },

        isUserSelected(id){ return this.newChatGroup.users_ids.includes(id) },

        hideWindow(){ this.$emit('hideCreateChatGroupDropdown') },
    }

}
</script>