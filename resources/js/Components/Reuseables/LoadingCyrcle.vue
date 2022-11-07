<template>
    <Transition name="loading">
        <LoadingIcon v-show="show" :class="['absolute inset-0 mx-auto ', iconCls]" />
    </Transition>
</template>

<script>
import LoadingIcon from "@/Components/Reuseables/Icons/LoadingIcon.vue"

/**
 * Parent element of this component has to have position relative.
 * iconCls is a string of classes.
 * show is boolean
 */

export default {
    props: [ 'show', 'iconCls' ],
    components: { LoadingIcon, },
}
</script>

<style scoped>
.loading-enter-active {
    transition: all 0.1s ease-out;
}

.loading-leave-active {
    transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.loading-enter-from,
.loading-leave-to {
    transform: translateY(-20px);
    opacity: 0;
}
</style>