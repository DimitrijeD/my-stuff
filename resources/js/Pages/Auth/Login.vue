<template>
    <div class="container-focus-center">
        <form v-on:submit.prevent="login" class="w-full space-y-2">
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
            
            <div>
                <router-link class="text-gray-500 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-400  float-right" to="/forgot-password">Forgot password?</router-link>
            </div>

            <button 
                type="submit"
                class="btn-auth"
            >Login</button>
        </form>
    </div>
</template>

<script>
import TextInput from '@/Components/Reuseables/TextInput.vue'
import * as ns from '@/Store/module_namespaces.js'

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
            errors: {}
        }
    },

    created(){
        
    },

    methods:
    {
        login(){
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('login', this.form).then((res) => {
                    localStorage.setItem("token", res.data.data.token)
                    this.$store.dispatch('storeUser', res.data.data.user)
                    this.$router.push({ path: '/profile' });
                    this.$store.dispatch(ns.actionResponse('main', 'inject'), res.data)

                }).catch((error) =>{
                    this.errors = error.response.data.messages
                })
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