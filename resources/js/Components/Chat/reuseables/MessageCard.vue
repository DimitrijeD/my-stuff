<template>
    <div v-if="messageExists" class="h-full flex flex-col p-2 space-y-1 ">
        <small-user 
            :user="message.user" 
            :imgCls="'w-8 h-8'" 
            class="flex-none"
        />

        <div :class="['grow py-1.5 bg-gradient-to-b from-gray-300 to-gray-100 dark:from-darker-200 dark:from-darker-300 rounded-md', !seen ? 'green-glow' : 'shadow shadow-gray-500 dark:shadow-darker-100/10']">
            <p class="multi-line-text-elipsis break-words py-0.5 px-2">
                {{ message.text }}
            </p>
        </div>
        <MomentsAgo class="def-moments-ago ml-auto pb-1 pr-2" :date="message.created_at" />
    </div>

    <div v-else class="h-full flex flex-col">
        <p class="m-auto italic">{{ noMessageText }}</p>
    </div>                
</template>

<script>
import SmallUser from '@/Components/Reuseables/SmallUser.vue';
import MomentsAgo from '@/Components/Reuseables/MomentsAgo.vue';

export default {
    props: [ 'message', 'seen', ],

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