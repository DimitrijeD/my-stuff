<template>
    <div class="flex gap-x-2 m-3 select-none">
        <ThreeDotsIcon @click="toggleConfig()" :class="[ ' cw-head-btn-fill', window.showConfig && !window.minimized ? 'cw-head-btn-fill-active' : '' ]" />
        <DoubleArrowIcon @click="toggleWindow()" :class="[ 'cw-head-btn-fill', window.minimized ? 'transform rotate-180': '', ]" />
        <CloseIcon @click="close()" :class="[ 'cw-head-btn-stroke', ]"  />
    </div>
</template>

<script>
import ThreeDotsIcon from "@/Components/Reuseables/Icons/ThreeDotsIcon.vue"
import DoubleArrowIcon from "@/Components/Reuseables/Icons/DoubleArrowIcon.vue"
import CloseIcon from "@/Components/Reuseables/Icons/DeleteIcon.vue"

export default {
    inject: ['group_id'],

    components: { ThreeDotsIcon, DoubleArrowIcon, CloseIcon, },

    computed: {
        window(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'window') ]
        }
    },

    methods:{
        toggleConfig() { this.$store.dispatch(ns.groupModule(this.group_id, 'toggleConfig'), this.group_id) },
        
        toggleWindow() { this.$store.dispatch(ns.groupModule(this.group_id, 'toggleWindow'), this.group_id) },

        close() { this.$store.dispatch(ns.groupsManager('closeGroup'), this.group_id) },

    },

}
</script>

<style scoped>
.cw-head-btn{ @apply def-trans w-7 h-full cursor-pointer; }

.cw-head-btn-fill{ @apply cw-head-btn stroke-transparent
    fill-white hover:fill-gray-200
    dark:fill-gray-400 dark:hover:fill-gray-200; }

.cw-head-btn-fill-active{ @apply fill-green-400 hover:fill-green-300
    dark:fill-blue-400 dark:hover:fill-blue-300;}

.cw-head-btn-stroke{ @apply cw-head-btn fill-transparent
    stroke-white hover:stroke-gray-300 
    dark:stroke-gray-400 dark:hover:stroke-gray-200; }
</style>