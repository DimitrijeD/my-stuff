<template>
    <div class="grid grid-cols-2 gap-4 ">
        <p class="left">Group name</p>

        <div class="right">
            <p v-if="group.name">{{ group.name }}</p>
            <p v-else class="italic">This group has no name</p>
        </div>

        <p class="left">Number of participants</p>

        <p class="right">{{ getNumberOfParticipants(group.participants) }}</p>

        <p class="left">Group created</p>

        <p class="right">{{ formatDate() }}</p>

        <p class="left">Group type</p>

        <p class="right">{{ group.model_type }}</p>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    props: [ 'group', 'permissions' ],

    data() {
        return {
            
        }
    },

    computed: {
        ...mapGetters({ 
            user: "user",
        }),
    },

    methods: 
    {
        formatDate(){ return new Date(this.group.created_at) },

        getNumberOfParticipants(participants){ return Object.keys(participants).length },

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