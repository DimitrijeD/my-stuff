<template>
    <MainLayout >
        <template #nav >
            <Nav />
        </template>

        <template #view >
            <router-view />
            <Chat v-if="user?.email_verified_at" />
            <ActionResponseList 
                :moduleId="'main'" 
                :dieAfter="15" 
                :cardCls="'w-[90%] h-28 md:w-104 lg:w-120'"
                class="fixed left-0 right-0 top-16 md:left-auto md:right-4" 
            />
        </template>
    </MainLayout>
</template>

<script>
import { mapGetters } from 'vuex'
import MainLayout from '@/Layouts/MainLayout.vue';

import Chat from '@/Components/Chat/Chat.vue';
import Nav from  '@/Components/Nav/Nav.vue';
import ActionResponseList from '@/Components/ActionResponse/ActionResponseList.vue';

export default {
    components: { MainLayout, Chat, Nav, ActionResponseList, },

    computed: {
        ...mapGetters({ 
            user: "user",
        }),
    },

    created(){
        this.$store.dispatch('getUser')
    },
}
</script>