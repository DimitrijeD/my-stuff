<template>
    <div class="max-h-full cursor-pointer grid place-items-center" @click="changeViewIndex()">
        <ArrowHeadIcon :class="[
            'change-current-image-icon',
            directionCls,
            hasMore ? 'has-next-icon' : 'no-next-icon'
        ]" />
    </div>
</template>

<script>
import ArrowHeadIcon from '@/Components/Reuseables/Icons/ArrowHeadIcon.vue';

export default {
    props: [ 'directionCls', 'index', 'hasMore' ],

    components: { ArrowHeadIcon },

    methods: {
        changeViewIndex(){
            this.$emit('changeViewIndex')
        },
    },
}
</script>

<style scoped>
.change-current-image-icon{
    @apply w-10 h-10 overflow-hidden ;
}

.has-next-icon{
    @apply fill-gray-400 opacity-80 hover:opacity-90 hover:opacity-90;
}

.no-next-icon{
    @apply fill-gray-500 opacity-30;
}

</style>