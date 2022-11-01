<template>
    <div class="container-focus-center ">
        <form v-on:submit.prevent="resetPassword" enctype="multipart/form-data" class="w-full space-y-2">
            <TextInput 
                :name="'password'"
                :type="'password'"
                :placeholder="'Password'"
                :errors="errors.password"
                :required="true"
                @inputC="bindFormInput"
            />

            <TextInput 
                :name="'password_confirmation'"
                :type="'password'"
                :placeholder="'Confirm Password'"
                :errors="errors.password_confirmation"
                :required="true"
                @inputC="bindFormInput"
            />

            <button type="submit" class="btn-auth">Reset password</button>
        </form> 
    </div>
</template>

<script>
import TextInput from '@/Components/Reuseables/TextInput.vue'
import * as ns from '@/Store/module_namespaces.js'

export default {
    components:{
        TextInput,
    },

    data(){
        return{
            previewImgUrl: null,
            form:{
                password:'',
                password_confirmation:'',
                email: '',
                token: ''
            },
            errors:[],
        }
    },

    created(){
        const { email, token } = this.$route.query

        if(!email || !token) this.$router.push({ path: '/404' })
        
        this.bindFormInput({
            key: 'email',
            value: email
        })

        this.bindFormInput({
            key: 'token',
            value: token
        })
    },

    methods:{
        bindFormInput(data){
            this.form[data.key] = data.value
        },

        resetPassword(){
            this.$store.dispatch('resetRassword', this.collectInput()).then(() => {
                this.resetForm()
            }).catch(error => {
                this.errors = error.response.data.messages
            })
        },


        collectInput(){
            let data = new FormData;

            for(let i in this.form){
                data.append(i, this.form[i])    
            }

            return data
        },

        resetForm(){
            for(let i in this.form){
                this.form[i] = null
            }
        }

    },

}
</script>
