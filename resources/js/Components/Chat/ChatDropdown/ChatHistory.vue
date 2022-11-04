<template>
    <DefaultCardLayout class="space-y-2" :contentGapCls="'space-y-3'">
        <template #header >
            <input type="text" hidden><!-- For what ever reason, Chrome started suggesting password for input below, this fixed that issue -->
            <input
                class="small-input"
                placeholder="Find chats by user or chat name"
                type="text"
                v-model="searchStr"
                @keyup="searchInput()"
                autocomplete="off"
            >
        </template>

        <template #content>
            <GroupCard
                v-for="group_id in filteredGroupsIds" 
                :key="group_id"
                @click.native="openChatWindow(group_id)"
                :group_id="group_id"
            />

            <div v-if="nothingFound" class="h-full grid place-items-center">
                <p class="text-center font-light text-xl">
                    {{ nothingFound }} 
                </p>
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
            this.$store.dispatch(ns.groupsManager('openGroup'), {group_id: group_id, initiatedBy: 'user'}).then(() =>{
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