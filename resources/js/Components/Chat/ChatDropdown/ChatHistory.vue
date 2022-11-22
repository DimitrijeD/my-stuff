<template>
    <DefaultCardLayout class="space-y-3" :contentGapCls="'space-y-3'">
        <template #header >
            <div class="w-full relative">
                <input
                    class="search-loop bg-gray-200 text-gray-700 dark:text-gray-300 dark:bg-darker-300 dark:border-darker-400"
                    placeholder="Find chats by user or chat name"
                    type="text"
                    v-model="searchStr"
                    @keyup="searchInput()"
                    autocomplete="none"
                >

                <SearchIcon class="w-8 h-full p-1 absolute left-1 top-0 fill-blue-500 dark:fill-blue-400 opacity-80" />
            </div>
        </template>

        <template #body>
            <TransitionGroup tag="div" name="list" class="space-y-3 py-1 relative">
                <GroupCard
                    v-for="group_id in filteredGroupsIds" 
                    :key="group_id"
                    :group_id="group_id"
                    @click.native="openChatWindow(group_id)"
                />
            </TransitionGroup>

            <p v-if="nothingFound" class="h-full grid place-items-center text-center font-light text-xl">
                {{ nothingFound }} 
            </p>
        </template>
    </DefaultCardLayout>
</template>

<script>
import { mapGetters } from "vuex"
import GroupCard from "@/Components/Chat/reuseables/GroupCard.vue"
import DefaultCardLayout from '@/Layouts/DefaultCardLayout.vue';
import SearchIcon from '@/Components/Reuseables/Icons/SearchIcon.vue'

export default {
    
    components: { DefaultCardLayout, GroupCard, SearchIcon, },

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

<style scoped>

.list-move,
.list-enter-active,
.list-leave-active {
  transition: all 0.4s cubic-bezier(0.55, 0, 0.1, 1);
}
.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: scaleY(0.02) translateX(-100px);
}

.list-leave-active {
  position: absolute;
  width: 100%;

}
</style>