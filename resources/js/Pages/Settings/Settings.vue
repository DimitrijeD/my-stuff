<template>
    <div class="page-space-top grow h-full flex flex-col sm:flex-row font-light select-none mx-2 sm:mx-4 md:mx-8 xl:mx-20 ">

        <!-- This aside and Drop comp below are doing aboslutely same thing. I just dont know how to convert a list into dropdown in mobile -->
        <aside class="mb-2 sm:mr-2 sm:mb-0 hidden sm:block">
            <p class="text-lg pb-3 pl-2 pr-3">Settings</p>
            <button 
                v-for="(component, index) in components" 
                @click="show(index)"
                class="p-2 block rounded w-full text-left" 
                :class="component.show ? 'text-blue-500 dark:text-blue-400' : ''"
            >{{ component.name }}</button>
        </aside>

        <DropDownInput 
            @selected="openSetting"
            :current="getCurrent"
            :items="formatList()"
            class="mb-2 w-full sm:hidden bg-gray-200 dark:bg-darker-300"
            :headCls="'p-4 border border-gray-400 dark:border-darker-300 rounded-lg'" 
            :arrowCls="'w-6 h-6 fill-gray-300 stroke-gray-500'" 
            :itemsWrapCls="'z-10 py-2 bg-gray-200 dark:bg-darker-200 '" 
            :itemCls="'py-2 px-4 mx-2 hover:bg-gray-100 dark:hover:bg-darker-100 rounded-lg'"
        />
        <!-- So aside is only visible on sm, dropdown only visible on mobile -->

        <div class="w-full grow bg-gray-100/50 dark:bg-darker-100 p-4 sm:p-8 md:px-10 sm:gap-2 rounded">
            <component :is="getCurrent" :user="user" />
        </div>
    </div>
</template>

<script>
import Account from '@/Pages/Settings/Account.vue';
import Appearance from '@/Pages/Settings/Appearance.vue';
import Notifications from '@/Pages/Settings/Notifications.vue';
import DropDownInput from '@/Components/Reuseables/DropDownInput.vue'
import Colorz from '@/Pages/Settings/Colorz/Colorz.vue'
import { mapGetters } from 'vuex';

export default {
    components: { Account, Appearance, Notifications, DropDownInput, Colorz },

    data(){
        return {
            components: [
                {
                    show: true,
                    name: 'Account'
                }, 
                {
                    show: false,
                    name: 'Appearance'
                }, 
                {
                    show: false,
                    name: 'Notifications'
                }, 
                {
                    show: false,
                    name: 'Colorz'
                }
            ],

        }
    },

    computed: {
        ...mapGetters({ 
            user: "user",
        }),

        getCurrent(){
            for(let i in this.components){
                if(this.components[i].show) return this.components[i].name
            }
        },
    },

    methods: {
        show(com){
            for(let i in this.components){
                this.components[i].show = false
            }

            this.components[com].show = true
        },

        formatList(){
            let list = []

            for(let i in this.components){
                list.push(this.components[i].name) 
            }

            return list
        },

        openSetting(name){
            for(let i in this.components){
                this.components[i].show = this.components[i].name == name 
                    ? true
                    : false
            }
        }
    }
}
</script>