<template>
    <div class="grid grid-cols-2 gap-4 ">
        <p class="left">Group name</p>

        <div class="right">
            <p v-if="name">{{ name }}</p>
            <p v-else class="italic">This group has no name</p>
        </div>

        <p class="left">Number of participants</p>

        <p class="right">{{ numberOfParticipants }}</p>

        <p class="left">Group created</p>

        <p class="right">{{ formatDate() }}</p>

        <p class="left">Group type</p>

        <p class="right">{{ model_type }}</p>
    </div>
</template>

<script>
export default {
    inject: ['group_id'],

    props: [ 'permissions' ],

    computed: {
        name(){ 
            return this.$store.getters[ ns.groupModule(this.group_id, 'name') ] 
        },

        model_type(){ 
            return this.$store.getters[ ns.groupModule(this.group_id, 'model_type') ] 
        },

        created_at(){ 
            return this.$store.getters[ ns.groupModule(this.group_id, 'created_at') ] 
        },

        numberOfParticipants(){ 
            return this.$store.getters[ ns.groupModule(this.group_id, 'numberOfParticipants') ] 
        },

    },

    methods: {
        formatDate(){ return new Date(this.created_at) },
    }
}
</script>

<style scoped>
    .left {
        @apply select-none;
    }

    .right {
        @apply select-all cursor-pointer;
    }
</style>