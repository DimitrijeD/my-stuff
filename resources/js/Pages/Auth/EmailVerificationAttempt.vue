<template>
    <div class="grid place-items-center h-screen">
        <div class="w-3/4">

            <div v-if="status == 'success'">
                <p class="auth-status text-blue-500">
                    {{ message }}
                </p>
            </div>

            <div v-if="status == 'error'">
                <p class="auth-status text-yellow-600 ">
                    {{ message }}
                </p>

                <p v-if="email" class="text-center text-yellow-600 bg-gray-100 px-2 py-8 text-xl font-normal">
                    {{ email }}
                </p>

                <button
                    class="p-4 bg-blue-400 hover:bg-blue-500 text-gray-100 hover:text-white w-full text-lg"
                    @click="resendEmailVerification"
                >
                    Click here to request another verification link.
                </button>
            </div>

            <div v-if="status == ''">
                <div class="text-center text-gray-500 bg-gradient-to-b from-gray-200 to-gray-100">
                    <p class="px-3 py-8 text-2xl font-normal">
                        An error occured during email verification :/
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default{
    data(){
        return {
            user_id: null,
            code: '',

            status: '',
            message: '',
            email: '',
        }
    },

    created(){
        this.user_id = this.$route.params.user_id;
        this.code    = this.$route.params.code;
        this.verifyEmail()
    },
    
    methods:{
        verifyEmail(){
            axios.get(`email-verification/uid/${this.user_id}/c/${this.code}`).then( res => {
                this.status = res.data.status
                this.message = res.data.message
                this.email = res.data.user.email

                this.$store.dispatch('storeUser', res.data.user)

                setTimeout(()=>{
                    this.$router.push({ path: '/profile' });
                }, 4000);

            }).catch( error => {
                this.$router.push({ path: '/404' });
            });
        },

        resendEmailVerification(){
            axios.post('email-verification/create-or-update', {email: this.email}).then((res)=>{
                this.message = 'Another email has been sent. Please check if you inserted correct email.';
            }).catch((error) =>{
                if(error.response.status == 429){
                    this.status = "error"
                    this.message = "Please wait for 1 minute before requesting another email.";
                }
            });
        },
    }

}

</script>
