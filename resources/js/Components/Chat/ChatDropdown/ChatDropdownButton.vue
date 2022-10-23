<template>
    <MailIcon 
        @click="toggleMainDropdown()"
        :class="[
            'h-[3rem] w-[3rem]',
            'stroke-transparent fill-blue-500 hover:fill-blue-600 dark:fill-gray-400 dark:hover:fill-gray-300', 
            numGroupsWithUnseen ? 'fill-green-600 dark:fill-green-600' : '',
            dropdown.isOpened ? '' : '',
        ]"
    />
</template>

<script>
import { mapGetters } from "vuex"
import * as ns from '@/Store/module_namespaces.js'
import MailIcon from "@/Components/Reuseables/Icons/MailIcon.vue"

export default {
    
    components: {
        MailIcon,
    },

    data(){
        return {
            
        }
    },

    computed: {
        ...mapGetters({ 
            numGroupsWithUnseen: ns.groupsManager() + "/numGroupsWithUnseen",
            dropdown: ns.groupsManager() + "/dropdown",
        }),
    },

    mounted() {
        
    },

    methods: {
        toggleMainDropdown(){
            this.$store.dispatch(ns.groupsManager() + "/toggleMainDropdown")
        },
    },
}
</script>