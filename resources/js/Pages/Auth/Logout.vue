<template>
    <button class="profile-dropdown-list-item" @click.prevent="logout">Logout</button>
</template>

<script>
export default {
    methods: {
        logout(){
            localStorage.clear()
            
            this.$store.dispatch("logout").then(()=> {
                this.$router.push({ name: 'Home' })
            })
        },
    },
}
</script>
