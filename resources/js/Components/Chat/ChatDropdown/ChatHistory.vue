<template>
    <DefaultCardLayout class="space-y-2 ">
        <template #header >
            <input
                class="small-input"
                placeholder="Find chats by user or chat name"
                type="text"
                v-model="searchStr"
                @keyup="searchInput()"
            >
        </template>

        <template #content>
            <div v-for="group_id in filteredGroupsIds" :key="group_id" class="">
                <GroupCard
                    @click.native="openChatWindow(group_id)"
                    :group_id="group_id"
                />
            </div>

            <div v-if="nothingFound" class="m-2 text-red-500">
                {{ nothingFound }} 
            </div>
        </template>
    </DefaultCardLayout>
</template>

<script>
import { mapGetters } from "vuex"
import * as ns from '@/Store/module_namespaces.js'
import GroupCard from "@/Components/Chat/reuseables/GroupCard.vue"
import DefaultCardLayout from '@/Layouts/DefaultCardLayout.vue';

export default {
    
    components: {
        DefaultCardLayout,
        GroupCard,
    },

    data(){
        return {
            searchStr: '',
            nothingFound: '',
        }
    },

    computed: {
        ...mapGetters({ 
            filteredGroupsIds: ns.groupsManager('filteredGroupsIds'),
        }),
    },

    methods: {
        openChatWindow(group_id){
            this.$store.dispatch(ns.groupsManager('openGroup'), group_id).then(() =>{
                this.$emit('closeDropdown')
            })
        },

        searchInput(){
            this.nothingFound = '';
            this.$store.dispatch(ns.groupsManager('filterGroupsBySearchString'), this.searchStr)

            if(!this.filteredGroupsIds.length) this.nothingFound = 'Nothing found :/';
        },
    }
}
</script>