<template>
    <div class="container-focus-center ">
        <form v-on:submit.prevent="register" enctype="multipart/form-data" class="w-full space-y-2">

            <text-input 
                :name="'first_name'"
                :placeholder="'First name'"
                :errors="errors.first_name"
                :required="true"
                @inputC="bindFormInput"
            />

            <text-input 
                :name="'last_name'"
                :placeholder="'Last name'"
                :errors="errors.last_name"
                :required="true"
                @inputC="bindFormInput"
            />

            <text-input 
                :name="'email'"
                :type="'email'"
                :placeholder="'Email'"
                :errors="errors.email"
                :required="true"
                @inputC="bindFormInput"
            />

            <text-input 
                :name="'password'"
                :type="'password'"
                :placeholder="'Password'"
                :errors="errors.password"
                :required="true"
                @inputC="bindFormInput"
            />

            <text-input 
                :name="'password_confirmation'"
                :type="'password'"
                :placeholder="'Confirm Password'"
                :errors="errors.password_confirmation"
                :required="true"
                @inputC="bindFormInput"
            />

            <div>
                <div class="flex items-center gap-2">
                    <div class="flex-none" v-if="previewImgUrl">
                        <img :src="previewImgUrl" class=" w-16 h-16 object-cover border border-gray-100 rounded-full shadow-gray" />
                    </div>
                    <div class="grow">
                        <input ref="fileUpload" type="file" @change="onProfilePictureSelected" hidden>
                        <button type="button" class=" auth-input" @click="chooseFiles()">{{ form.profilePicture ? "Select another" : "Choose profile picture" }}</button>
                    </div>
                </div>
                <p class="text-red-500" v-for="(error, index) in errors.profilePicture" :key="index">
                    {{ error }}
                </p>
            </div>


            <button type="submit" class="btn-auth">Register</button>
        </form> 
    </div>
</template>

<script>
import TextInput from '@/Components/Reuseables/TextInput.vue'

export default {
    components:{
        "text-input": TextInput,
    },

    data(){
        return{
            previewImgUrl: null,
            form:{
                first_name: '',
                last_name: '',
                email: '',
                password:'',
                password_confirmation:'',
                profilePicture: null,
            },
            errors:[],
        }
    },

    methods:{
        chooseFiles() {
            this.$refs['fileUpload'].click()
        },

        bindFormInput(data)
        {
            this.form[data.key] = data.value
        },

        register()
        {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('register', this.collectInput()).then((res) =>{
                    this.$store.dispatch("storeUser", res.data.user).then(()=> {
                        localStorage.setItem("token", res.data.token)
                        this.$router.push({ path: '/email-verification/init' });
                    })
                }).catch((error) =>{
                    this.errors = error.response.data.errors;
                });
            });
        },

        onProfilePictureSelected(e)
        {
            this.form.profilePicture = e.target.files[0];
            this.previewImgUrl = URL.createObjectURL(this.form.profilePicture);
        },

        collectInput()
        {
            let data = new FormData;

            data.append('first_name',            this.form.first_name);
            data.append('last_name',             this.form.last_name);
            data.append('email',                 this.form.email);
            data.append('password',              this.form.password);
            data.append('password_confirmation', this.form.password_confirmation);
            data.append('profilePicture',        this.form.profilePicture);

            return data
        },

    },

}
</script>
