<template>
    <div class="flex justify-start ml-2">
        <div v-for="participant in participants" :key="participant.id">           
             <!-- Dont display self in participant list -->
            <div v-if="participant.id != user.id" class="flex justify-start mr-2">
                <!-- @TODO Next div is just green circle as 'user is online' indicator;  replace after this is done dynamically -->
                <div class="m-auto">
                    <div class=" w-3 h-3 rounded-full bg-green-400" />
                </div>

                <div class="cw-head-text">
                    {{ participant.first_name }} {{ participant.last_name }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default{
    props: [
        'participants'
    ],

    computed: {
        ...mapGetters({ user: "user" }),
    },

    data(){
        return {

        }
    },

    methods: {

    },


}
</script>