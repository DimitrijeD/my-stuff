<template>
    <MainLayout >
        <template #nav >
            <Nav />
        </template>

        <template #view >
            <router-view />
            <Chat v-if="user" />
        </template>
    </MainLayout>
</template>

<script>
import { mapGetters } from 'vuex'
import MainLayout from '@/Layouts/MainLayout.vue';

import Chat from '@/Components/Chat/Chat.vue';
import Nav from  '@/Components/Nav/Nav.vue';

export default {
    components: {
        MainLayout,
        Chat,
        Nav,
    },

    data(){
        return {

        }
    },

    computed: {
        ...mapGetters({ 
            user: "user",
        }),
    },

    created(){
        this.$store.dispatch('getUser')
    },

    methods: {
        
    },
}
</script>