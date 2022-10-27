<template>
    <div class="container-focus-center">
        <div class="w-full">
            <p class="auth-status text-blue-500">
                One step away, 
            </p>

            <div :class="['text-center break-words text-lg py-16 shadow-inner dark:bg-blacker-100', status_type ? 'text-green-600' : 'text-red-400' ]">
                <p class="m-3">{{ status }}</p>
                <p>{{ user.email }}</p>
            </div>


            <p class="py-3 px-1 text-gray-600 dark:text-gray-400" v-if="!status">
                Before proceeding, please check your email for a verification link.
            </p>

            <button
                class="btn-auth text-gray-600 bg-gradient-to-t 
                from-gray-300 to-gray-200 hover:from-gray-400 hover:to-gray-300 
                dark:from-darker-200 dark:to-darker-50 dark:hover:from-darker-300 dark:hover:to-darker-50"
                @click="resendEmailVerification"
            >
                Click here to request another email.
            </button>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {

    data(){
        return {
            status: '',
            status_type: true,
        }
    },

    computed: {
        ...mapGetters({ 
            user: "user",
        }),
    },

    created(){
        this.getUnAuthUser() 
    },

    methods: {
        getUnAuthUser(){
            axios.get('email-verification/is-validated').then((res) => {
                if(res.data.status == "already_verified"){
                    this.$router.push({ path: '/profile' }) 
                }

                this.status = `verification email has been sent to`
                this.status_type = true
            }).catch((error) => {
                if(error.response.data?.message == "Unauthenticated.") return //@TODO
            });
        },

        resendEmailVerification(){
            axios.post('email-verification/create-or-update', {}).then((res)=>{
                this.status_type = Object.keys(res.data)[0] == "success" ? true : false
                this.status = res.data[Object.keys(res.data)[0]]
            }).catch((error) =>{
                if(error.response.status == 429){
                    this.status = "Please wait for 1 minute before requesting another email."
                    this.status_type = false
                }
            });
        }

    }
}
</script>
