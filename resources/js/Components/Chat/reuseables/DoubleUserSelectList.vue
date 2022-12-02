<template>
    <DoubleScrollContentCardLayout>
        <template #header >
            <SearchInput
                :actions="searchInput.actions"
                :exclude="searchInput.exclude"
                :placeholder="searchInput.placeholder"
                :cls="searchInput.cls"
                @typed="searchedAtLeastOnce()"
                @awaitingApi="awaitingApi"
            />
        </template>

        <template #content-left>
            <!-- <LoadingCyrcle :show="isAwaitingApi" :iconCls="'w-10 h-10 stroke-blue-500'" /> -->

            <TransitionGroup tag="div" name="list" class="relative">
                <template v-for="(id, index) in users" :key="`list-left_${index}`" >
                    <SmallUser                     
                        v-if="!selected.includes(id)"
                        :user="getUser(id)"
                        class="py-1 select-none"
                        @click.stop.prevent="toggleSelect(id)"
                    /> 
                </template>
            </TransitionGroup>

            <div v-if="infoTxt" class="h-full flex">
                <p class="info-txt">{{ infoTxt }}</p>
            </div>
        </template>

        <template #content-right>
            <TransitionGroup tag="div" name="list" class="relative">
                <SmallUser 
                    v-for="(id, index) in selected" 
                    :key="`list-right_${index}`"
                    :user="getUser(id)"
                    class="py-1 select-none text-green-500"
                    @click="toggleSelect(id)"
                />
            </TransitionGroup>

            <div v-if="selected.length == 0" class="h-full flex">
                <p class="info-txt">
                    Selected users will appear in this list.
                </p>
            </div>
        </template>
    </DoubleScrollContentCardLayout>
</template>

<script>
import SmallUser from '@/Components/Reuseables/SmallUser.vue';
import SearchInput from '@/Components/Chat/reuseables/SearchInput.vue'
import DoubleScrollContentCardLayout from '@/Layouts/DoubleScrollContentCardLayout.vue'
import LoadingCyrcle from '@/Components/Reuseables/LoadingCyrcle.vue'

export default {
    props: ['searchInput', 'flushSelected', 'stopLoadingIconOnFail' ],

    components: { SearchInput, SmallUser, DoubleScrollContentCardLayout, LoadingCyrcle },

    data() {
        return {
            selected: [],

            nothingFound: false,
            hasSearched: false,
            isAwaitingApi: false,
            hideLoadingAfterMS: 400,
        }
    },

    computed: {
        users(){ 
            return this.$store.getters[ns.users('getFilterForAddUsers')] 
        },

        infoTxt(){
            if(this.users.length != 0) return ''

            if(!this.hasSearched) return 'Users will appear in this list.'

            if(!this.isAwaitingApi) return 'Nothing found.'
        }
    },

    watch: {
        flushSelected(){
            this.selected = []
        },

        stopLoadingIconOnFail(){
            this.isAwaitingApi = false
        }
    },

    methods:{        

        getUser(id) { 
            return this.$store.getters[ns.users('getById')](id)
        },

        toggleSelect(id){
            this.selected.includes(id)
                ? this.selected.splice(this.selected.indexOf(id), 1)
                : this.selected.push(id)

            this.$emit('toggled', this.selected)
        },

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
    @apply select-none m-auto italic font-light text-gray-600 dark:text-gray-400;
}

.list-move,
.list-enter-active,
.list-leave-active {
  transition: all 0.3s cubic-bezier(0.55, 0, 0.1, 1);
}
.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: scaleY(0.01) translateX(-200px);
}

.list-leave-active {
  position: absolute;
}

</style>