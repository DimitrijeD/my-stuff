<template>
    <span>
        <MomentsAgo :date="date" />
    </span>
</template>

<script>
import MomentsAgo from '@/Components/MomentsAgo/MomentsAgo.vue'

export default{
    props: [ 'date' ],

    components: {MomentsAgo},

    methods:{

    }
}
</script>