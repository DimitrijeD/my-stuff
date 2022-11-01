<template>
    <div class="container-focus-center">
        <div class="w-full">
            <h1>Forgot password?</h1>
            <p class="def-paragraph mb-2">Enter your password in form and hit button to receive email</p>

            <form v-on:submit.prevent="forgotPasswordRequestEmail" class="w-full space-y-2">
                <TextInput 
                    :name="'email'"
                    :type="'email'"
                    :placeholder="'What is your email address?'"
                    :errors="errors.email"
                    :required="true"
                    @inputC="bindFormInput"
                />

                <button 
                    type="submit"
                    class="btn-auth"
                >Get password reset email</button>
            </form>
        </div>
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
            },
            errors: {}
        }
    },

    created(){
        
    },

    methods:
    {
        forgotPasswordRequestEmail(){
            this.$store.dispatch('forgotPassword', this.form)
        },

        bindFormInput(data){
            this.form[data.key] = data.value
        }

    }
}
</script>

<style>

</style>