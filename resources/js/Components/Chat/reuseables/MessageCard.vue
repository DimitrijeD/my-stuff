<template>
    <div v-if="messageExists" class="h-full flex flex-col space-y-2">
        <small-user 
            :user="message.user" 
            :imgCls="'w-8 h-8'" 
            class="flex-none"
        />

        <div class="grow py-2">
            <p class="m-auto multi-line-text-elipsis break-words">{{ message.text }} </p>
        </div>
        <MomentsAgo class="def-moments-ago ml-auto pr-2 pb-1" :date="message.created_at" />
    </div>

    <div v-else class="h-full flex flex-col">
        <p class="m-auto italic">{{ noMessageText }}</p>
    </div>                
</template>

<script>
import SmallUser from '@/Components/Reuseables/SmallUser.vue';
import MomentsAgo from '@/Components/Reuseables/MomentsAgo.vue';

export default {
    props: [ 'message', ],

    components: {
        SmallUser,
        MomentsAgo,
    },

    computed: {
        messageExists(){
            return this.message.id ? true : false
        }
    },

    data(){
        return {
            noMessageText: "No messages yet."
        }
    },

    created(){
        
    },

    methods: {

    },

}
</script>

<style>
.multi-line-text-elipsis{
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}



</style>