<template>
    <div class="container-focus-center">
        
    </div>
</template>

<script>

export default{

    created(){
        this.user_id = this.$route.params.user_id;
        this.code    = this.$route.params.code;
        this.verifyEmail({
            user_id: this.user_id,
            code: this.code
        })
    },
    
    methods:{
        verifyEmail(data){
            this.$store.dispatch('verifyEmail', data).then(res => {
                this.$router.push({ path: '/profile' })
            }).catch( error => {
                this.$router.push({ path: '/404' });
            })
            
        },
        
    }

}

</script>
