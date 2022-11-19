<template>
    <div class="space-y-10">
        <div class="space-y-4">
            <DropDownInput 
                @selected="selectedTheme"
                :current="user?.user_setting?.theme"
                :items="availableThemes"
                class="w-48 bg-gray-200 dark:bg-darker-300"
                :headCls="'p-4 border border-gray-400 dark:border-darker-300 rounded-lg'" 
                :arrowCls="'w-6 h-6 fill-gray-300 stroke-gray-500'" 
                :itemsWrapCls="'py-2 bg-gray-200 dark:bg-darker-200 '" 
                :itemCls="'py-2 px-4 mx-2 hover:bg-gray-100 dark:hover:bg-darker-100 rounded-lg'"
            />
        </div>

    </div>
</template>

<script>
import DropDownInput from '@/Components/Reuseables/DropDownInput.vue'
import { useDark, useToggle } from '@vueuse/core';

export default {
    props: ['user'],

    components: { DropDownInput, },

    data(){
        return {
            availableThemes: ['dark', 'light'],
            isDark: useDark(),
        }
    },

    methods: {
        selectedTheme(theme){
            if(theme.forHumans == 'light'){
                localStorage.setItem('vueuse-color-scheme', 'auto')
                this.isDark = false
            } else {
                localStorage.setItem('vueuse-color-scheme', 'dark')
                this.isDark = true
            }

            this.$store.dispatch('updateProfile', {
                settingsFiels: {
                    theme: theme.forHumans
                }
            })
        }
    },
}
</script>