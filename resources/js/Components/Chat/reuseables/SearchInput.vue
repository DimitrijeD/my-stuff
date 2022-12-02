<template>
    <div class="w-full relative">
        <input 
            type="text" 
            :class="[cls, 'search-loop']"
            v-model="searchStr"
            :placeholder="placeholder"
            @keydown.once="typed"
        >
        <SearchIcon class="w-8 h-full p-1 absolute left-1 top-0 fill-blue-500 dark:fill-blue-300 opacity-70" />
        
        <Transition name="loading">
            <LoadingIcon v-show="isAwaitingApi" class="w-8 h-full p-1 absolute right-1 top-0 stroke-blue-500 opacity-70" />
        </Transition>
    </div>
</template>

<script>
import SearchIcon from '@/Components/Reuseables/Icons/SearchIcon.vue'
import LoadingIcon from "@/Components/Reuseables/Icons/LoadingIcon.vue"

export default {
    props:[ 'actions', 'exclude', 'placeholder' , 'cls' ],

    components: { SearchIcon, LoadingIcon, },

    data() {
        return {
            searchStr: '',
            apiTimeout: null,
            apiRequestAfterMS: 1000,
            minStr: 3,
            isAwaitingApi: false,

            payload: {
                search_str: '',
                exclude: [],
                responseContext:{
                    moduleName: '',
                    important: null
                }
            }
        }
    },

    watch: {
        searchStr(){
            this.prepSearchString()
            this.searchStore()

            if(! this.canCallAPI()) return

            this.searchAPI()
        },

        exclude(){
            this.payload.exclude = this.exclude
        },

        isAwaitingApi(){
            this.$emit('awaitingApi', this.isAwaitingApi)
        }
    },

    created(){
        this.payload.exclude = this.exclude

        this.payload.responseContext = {
            moduleName: this.actions.responseModuleName,
            important: true
        }
    },

    methods: {
        typed(){
            this.$emit('typed')
        },

        prepPayload(){
            this.payload = {
                search_str: this.searchStr.trimEnd().trimStart(),
                exclude: this.exclude,
                responseContext:{
                    moduleName: this.actions.responseModuleName,
                    important: true
                }
            }
        },

        prepSearchString(){
            this.payload.search_str = this.searchStr.trimEnd().trimStart()
        },

        canCallAPI(){
            if(!this.actions?.api || this.payload.search_str.length < this.minStr) return false

            clearTimeout(this.apiTimeout)
            return true
        },

        searchStore(){
            if(this.actions?.store){
                // Inside store we have some data, first filter those
                this.$store.dispatch(this.actions.store, this.payload)
            }
        },

        searchAPI(){
            this.isAwaitingApi = true

            this.apiTimeout = setTimeout( () => {
                this.$store.dispatch(this.actions.api, this.payload).then(() => {
                    this.isAwaitingApi = false
                }).catch(() => {
                    this.isAwaitingApi = false
                })
            }, this.apiRequestAfterMS )
        }
    }
}
</script>


<style scoped>
.loading-enter-active {
    transition: all 0.1s ease-in;
}

.loading-leave-active {
    transition: all 0.3s ease-out;
}

.loading-enter-from,
.loading-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>