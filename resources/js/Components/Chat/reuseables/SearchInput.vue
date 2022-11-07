<template>
    <input 
        class="small-input"
        type="text" 
        @keyup="searchUser"
        v-model="searchStr"
        :placeholder="placeholder"
        @keydown.once="typed"
    >
</template>

<script>

export default {
    props:[ 'actions', 'exclude', 'placeholder' ],

    data() {
        return {
            searchStr: '',
            debounce: null,
            start: Date.now(),
            apiRequestAfterMS: 400,
            minStr: 3
        }
    },

    methods: {
        searchUser(){
            let payload = {
                search_str: this.searchStr,
                exclude:    this.exclude,
            }
            // Inside store we have some data, first filter those
            this.$store.dispatch(this.actions.store, payload)

            // only then ask api for records
            if(this.minStr > this.searchStr.trim().length) return

            clearTimeout(this.debounce)

            this.debounce = setTimeout( () => {
                this.$emit('awaitingApi', true)
                this.$store.dispatch(this.actions.api, payload).then(() => {
                    this.$emit('awaitingApi', false)
                })
            }, this.apiRequestAfterMS )
        },

        typed(){
            this.$emit('typed')
        },

    }
}
</script>