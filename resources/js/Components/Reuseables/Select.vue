<template>
    <div class="w-full relative" @mouseenter="show = true" @mouseleave="show = false">
        <slot name="selected"></slot>

        <div v-if="show" class="absolute h-46 scroll1 bg-red-900">
            <slot name="list"></slot>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            show: false
        }
    }
}

</script>