<template>
    <DefaultCardLayout>
        <template #header >
            <div class="space-y-2 mb-2 relative">    
                <input
                    class="small-input dark:bg-darker-200 dark:border-darker-300"
                    placeholder="Name of new group chat"
                    type="text"
                    v-model="newChatGroup.name"
                    autocomplete="none"
                >
            </div>
        </template>

        <template #body>
            <DoubleUserSelectList 
                :searchInput="{
                    actions: actions,
                    exclude: newChatGroup.users_ids, 
                    placeholder: 'Find users', 
                    cls: 'dark:bg-darker-200 dark:border-darker-300',
                }"
                @toggled="toggled"
                :flushSelected="flushSelected"
                :stopLoadingIconOnFail="stopLoadingIconOnFail"
            />
        </template>

        <template #footer>
            <div class="relative">
                <ActionResponseList 
                    :moduleId="`createChatGroup`" 
                    :dieAfter="15" 
                    :cardCls="'w-[90%] h-32 mx-auto'"
                    class="bottom-14 absolute z-10 mx-auto w-full" 
                />
                <button :class="['py-2 mt-2 def-btn', 
                    canCreate 
                        ? 'text-white bg-blue-500 hover:bg-blue-600 dark:text-green-300 dark:bg-darker-400 dark:hover:text-green-500' 
                        : 'cursor-not-allowed text-gray-600 bg-gray-200 dark:text-gray-500 dark:bg-darker-200', 
                    ]" 
                    @click="createNewChatGroup()"
                >
                    Create chat group
                </button>
            </div>
        </template>

    </DefaultCardLayout>
</template>

<script>
import { mapGetters } from 'vuex';
import DefaultCardLayout from '@/Layouts/DefaultCardLayout.vue'
import DoubleUserSelectList from '@/Components/Chat/reuseables/DoubleUserSelectList.vue'
import ActionResponseList from '@/Components/ActionResponse/ActionResponseList.vue';

export default {
    components: { DefaultCardLayout, DoubleUserSelectList, ActionResponseList },

    data() {
        return {
            newChatGroup: {
                name: '',
                users_ids: [],
                model_type: "",
            },

            actions: {
                api: ns.users('searchForAddUsersInApi'),
                store: ns.users('searchForAddUsersInStore'),
                responseModuleName: 'createChatGroup'
            },
            flushSelected: 0,
            stopLoadingIconOnFail: 0,
        }
    },

    computed: {
        default_type(){
            return this.$store.getters[ns.chatRules('default_type')] 
        },

        users(){ 
            return this.$store.getters[ns.users('getFilterForAddUsers')] 
        },

        canCreate(){ 
            return this.newChatGroup.users_ids.length > 0
        }
    },

    methods:{        
        createNewChatGroup(){
            if(this.newChatGroup.users_ids.length === 0) return

            this.newChatGroup.model_type = this.default_type

            this.$store.dispatch(ns.groupsManager('storeGroup'), this.newChatGroup).then(() => {
                this.resetComponentVars()

                this.$store.dispatch(ns.chatDropdown('toggle'))
            }).catch((error) => {
                this.stopLoadingIconOnFail++
            })
        },

        resetComponentVars(){
            this.newChatGroup = {
                name: '',
                users_ids: [],
                model_type: this.default_type,
            }
            this.triggerClearSelectedUsersList()
        },

        triggerClearSelectedUsersList(){
            this.flushSelected++
        },

        toggled(users){
            this.newChatGroup.users_ids = users
        },
    }

}
</script>

<style scoped>
.info-txt {
    @apply m-auto italic font-light text-gray-600 dark:text-gray-400;
}
</style>