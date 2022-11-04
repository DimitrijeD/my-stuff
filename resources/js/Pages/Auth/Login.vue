<template>
    <div class="container-focus-center">
        <form v-on:submit.prevent="login" class="w-full space-y-3">
            <TextInput 
                :name="'email'"
                :type="'email'"
                :placeholder="'Email'"
                :errors="errors.email"
                :required="true"
                @inputC="bindFormInput"
            />

            <TextInput 
                :name="'password'"
                :type="'password'"
                :placeholder="'Password'"
                :errors="errors.password"
                :required="true"
                @inputC="bindFormInput"
            />
            
            <router-link class="text-gray-500 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-400 float-right" to="/forgot-password">Forgot password?</router-link>

            <button type="submit" class="btn-auth">Login</button>
        </form>
    </div>
</template>

<script>
import TextInput from '@/Components/Reuseables/TextInput.vue'
import { useDark, useToggle } from '@vueuse/core';

export default {
    components: {
        TextInput,
    },

    data(){
        return{
            form:{
                email: '',
                password: ''
            },
            errors: {},
            isDark: useDark(),
        }
    },

    created(){
        
    },

    methods:{
        login(){
            this.$store.dispatch('login',this.form).then((res) => {
                if(res.data.data.user.user_setting.theme == 'light'){
                    localStorage.setItem('vueuse-color-scheme', 'auto')
                    this.isDark = false
                } else {
                    localStorage.setItem('vueuse-color-scheme', 'dark')
                    this.isDark = true
                }

                this.$router.push({ path: '/profile' })
            }).catch((error) =>{
                if(error.response.status == 422)
                    this.errors = error.response.data.messages
            })
        },

        bindFormInput(data){
            this.form[data.key] = data.value
        }

    }
}
</script>

<style>

</style>