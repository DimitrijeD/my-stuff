<template>
    <div v-if="user" class="container-narrow space-y-4">
        <div class="flex flex-col sm:flex-row gap-2 lg:gap-4 ">
            <img class="my-auto w-48 inline-block object-cover" :src="user.image" alt="no img">

            <h1 class="my-auto">{{ user?.first_name }} {{ user?.last_name }}</h1>
        </div>

        <div>
            <p>id {{ user.id }}</p>
            <p>email {{ user.email }}</p>
        </div>
        
    </div>
   
</template>

<script>
import { mapGetters } from "vuex";

export default {
    data(){
        return{
            
        };
    },

    created()
    {
        
    },

    computed: {
        ...mapGetters({ user: "user" }),
    },

    mounted()
    {
        
    },


    methods: {
        
    }

}
</script>
