<template>
    <input 
        class="small-input"
        type="text" 
        @keyup="searchUser"
        v-model="searchStr"
        :placeholder="placeholder"
    >
</template>

<script>

export default {
    props:[
        'actions', 'exclude', 'placeholder'
    ],

    data() {
        return {
            searchStr: '',
            debounce: null,
            start: Date.now(),
            apiRequestAfterMS: 1000,
            minStr: 3
        }
    },

    methods: 
    {
        searchUser(){
            if(this.searchStr.trim().length < 3) return

            let payload = {
                search_str: this.searchStr,
                exclude:    this.exclude,
            }
            // Inside store we have some data, first filter those
            this.$store.dispatch(this.actions.store, payload)
            // only then ask api for records

            if(this.minStr > this.searchStr.trim().length) return

            clearTimeout(this.debounce)

            this.debounce = setTimeout( () => this.$store.dispatch(this.actions.api, payload), this.apiRequestAfterMS )
        },

    }
}
</script>