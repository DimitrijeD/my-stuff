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
    </div>
</template>

<script>
import SearchIcon from '@/Components/Reuseables/Icons/SearchIcon.vue'
export default {
    props:[ 'actions', 'exclude', 'placeholder' , 'cls' ],

    components: {SearchIcon},

    data() {
        return {
            searchStr: '',
            debounce: null,
            apiRequestAfterMS: 1000,
            minStr: 3,
        }
    },

    watch: {
        searchStr(){
            let payload = {
                search_str: this.searchStr.trimEnd().trimStart(),
                exclude:    this.exclude,
                responseContext:{
                    moduleName: this.actions.responseModuleName,
                    important: true
                }
            }

            if(this.actions?.store){
                // Inside store we have some data, first filter those
                this.$store.dispatch(this.actions.store, payload)
            }

            if(payload.search_str.length < this.minStr) return

            clearTimeout(this.debounce)

            if(this.actions?.store){
                this.$emit('awaitingApi', true)
                this.debounce = setTimeout( () => {
                    this.$store.dispatch(this.actions.api, payload).then(() => {
                        
                        this.$emit('awaitingApi', false)
                    }).catch(() => {
                        this.$emit('awaitingApi', false)
                    })
                }, this.apiRequestAfterMS )
            }
        }
    },

    methods: {
        typed(){
            this.$emit('typed')
        },

    }
}
</script>