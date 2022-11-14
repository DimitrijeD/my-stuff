<template>
    <div class="py-1">
        <div v-for="id in usersTyping" :key="id" >
            <span class="italic text-sm text-gray-500 dark:text-gray-400 ml-2 select-none">
                {{ getDisplay(id) }} is typing ...
            </span>
        </div>
    </div>
</template>

<script>

export default {
    props:[ 'group_id', 'participants'],

    /**
     * usersTyping = {
     *     int user1_Id: user1,
     *     int user2_Id: user2,
     *     ...
     * }
     *
     * Each time user1 types, his value and timer is reset.
     * When user stops typing for 'config.removeTyperAfterMS' milliseconds, his name will dissapear from DOM.
     * Feature is supposed to work for any number of chat participants utilizing hash table 'usersTyping'.
     *
     */
    data(){
        return{
            usersTyping: {},
            usersTimeouts: {},
            config: {
                removeTyperAfterMS: 900000,
                showNtypers: 5, // @TODO Maximum number of typers to show in chat 
            },
        }
    },

    mounted() {
        Echo.private("group." + this.group_id).listenForWhisper('typing', data => {
            if(data.isTyping)
                this.addOrUpdateTyper(data.id)
            else
                this.removeTyper(data.id)
        })
    },

    methods:{
        addOrUpdateTyper(id){
            this.usersTyping[id] = id

            this.addOrResetUser(id)
        },

        removeTyper(id){ 
            delete this.usersTyping[id]
        },

        addOrResetUser(id){
            if(this.usersTimeouts[id]) clearTimeout(this.usersTimeouts[id])

            this.usersTimeouts[id] = setTimeout(() => {
                this.removeTyper(id)
            }, this.config.removeTyperAfterMS)
        },

        getDisplay(id){
            return `${this.participants[id]?.first_name}`
        }
    },

}
</script>
