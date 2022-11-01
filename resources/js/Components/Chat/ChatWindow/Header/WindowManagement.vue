<template>
    <div class="grid grid-cols-3 gap-x-2 my-3 mr-3 select-none">
        <ThreeDotsIcon @click="openConfig()" :class="[ 'cw-head-btn-fill', group.window.showConfig && !group.window.minimized ? 'cw-head-btn-fill-active' : '' ]" />
        <DoubleArrowIcon @click="minimize()" :class="[ 'cw-head-btn-fill', group.window.minimized ? 'transform rotate-180': '', ]" />
        <CloseIcon @click="close()" :class="[ 'cw-head-btn-stroke', ]"  />
    </div>
</template>

<script>
import * as ns from '@/Store/module_namespaces.js'

import ThreeDotsIcon from "@/Components/Reuseables/Icons/ThreeDotsIcon.vue"
import DoubleArrowIcon from "@/Components/Reuseables/Icons/DoubleArrowIcon.vue"
import CloseIcon from "@/Components/Reuseables/Icons/DeleteIcon.vue"

export default {
    props:[ 'group', ],

    data(){
        return {
            gm_ns: ns.groupModule(this.group.id)
        }
    },

    components: {
        ThreeDotsIcon,
        DoubleArrowIcon,
        CloseIcon,
    },

    methods:{
        openConfig() { this.$store.dispatch(this.gm_ns + '/toggleConfig', this.group.id) },
        
        minimize() { this.$store.dispatch(this.gm_ns + '/toggleWindow', this.group.id) },

        close() { this.$store.dispatch(ns.groupsManager('closeGroup'), this.group.id) },

    },

}
</script>