<template>
    <MainLayout>
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
import { useDark, useToggle } from '@vueuse/core';
import { Colorz } from '@/Components/ColorCalculators/Colorz/Colorz.js'
import defaultConfig from '@/Pages/Settings/Colorz/types/defaultConfig.js'

export default {
    provide: {
        headerHeight: 3*16 // 3rem
    },

    components: { MainLayout, Chat, Nav, ActionResponseList, },

    computed: {
        ...mapGetters({ 
            user: "user",
        }),
    },

    data(){
        return {
            isDark: useDark(),
        }
    },

    created(){
        // this.$store.dispatch('getUser').then(()=>{
        //     let colorScheemas = []
        //     let allRules = ''
            
        //     if(this.user?.user_setting?.colorz){
        //         colorScheemas = this.user.user_setting.colorz
        //     } else {
        //         colorScheemas = defaultConfig
        //     }

        //     for(let i in colorScheemas){
        //         let cc = new Colorz(colorScheemas[i])
        //         cc.make() 

        //         allRules += cc.globalRules
        //     }

        //     Colorz.replaceRules(allRules)

        //     if(this.user.user_setting.theme == 'light'){
        //         localStorage.setItem('vueuse-color-scheme', 'auto')
        //         this.isDark = false
        //     } else {
        //         localStorage.setItem('vueuse-color-scheme', 'dark')
        //         this.isDark = true
        //     }
        // })
    },
}
</script>