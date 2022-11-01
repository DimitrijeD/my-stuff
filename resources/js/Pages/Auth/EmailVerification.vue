<template>
    <div class="container-focus-center">
        <div class="w-full">
            <p class="auth-status p-20">
                One step away
            </p>

            <button class="btn-auth" @click="resendEmailVerification">
                Click here to request another email
            </button>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {

    computed: {
        ...mapGetters({ 
            user: "user",
        }),
    },

    created(){
        this.checkIfValidated() 
    },

    methods: {
        checkIfValidated(){
            this.$store.dispatch('checkIfValidated').then(res => {

                if(res.data.data.status == "already_verified") this.$router.push({ path: '/profile' })
            })
        },

        resendEmailVerification(){
            this.$store.dispatch('resendEmailVerification').then(res => {

            })
        },

    }
}
</script>
