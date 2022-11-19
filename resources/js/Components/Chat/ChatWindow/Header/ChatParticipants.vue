<template>
    <div class="w-full m-auto truncate">
        <div class="ml-2">
            <ListChatParticipants v-if="showComponent == 'list-chat-participants'" :participants="participants"  />
            
            <GroupName v-if="showComponent == 'group-name'" :group_name="group_name" />

            <div class="cw-head-text" v-if="showComponent == 'default-show'">{{ defaultText }}</div>
            <div class="cw-head-text" v-if="showComponent == 'many-user-text'">{{ manyUserText }}</div>
        </div>
    </div>
</template>

<script>
import ListChatParticipants from "@/Components/Chat/ChatWindow/Header/ChatParticipants/ListChatParticipants.vue";
import GroupName from "@/Components/Chat/ChatWindow/Header/ChatParticipants/GroupName.vue";

export default {
    inject: ['group_id'],

    components: { ListChatParticipants, GroupName, },

    data(){
        return{
            showComponent: null,
            defaultText: "A Quiet Place",
        }
    },

    computed: {
        manyUserText(){
            return `Group with ${Object.keys(this.participants).length} participants`
        },

        participants(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'participants') ]
        },

        group_name(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'name') ]
        },

        model_type(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'model_type') ]
        },
    },

    created() {
        this.whatToShowInHeader()
    },

    watch: {
        // these 2 watchers make sure header content is reset when participants or group name are changed
        participants(){
            this.whatToShowInHeader()
        },

        group_name(){
            this.whatToShowInHeader()
        },
    },

    methods:{
        /**
         * Determine what content should be displayed in Chat Window Header
         * 
         * List of users or group name.
         */
        whatToShowInHeader(){
            if(this.group_name){
                this.showComponent = 'group-name'
                return
            } 

            if(this.group_name == "PRIVATE"){
                this.showComponent = 'list-chat-participants'
                return
            }

            const numParticipants = Object.keys(this.participants).length

            if(numParticipants == 1){
                this.showComponent = 'default-show'
                return
            }

            if(numParticipants > 1 && numParticipants <= 3){
                this.showComponent = 'list-chat-participants'
                return
            }

            if(numParticipants >= 4){
                this.showComponent = 'many-user-text'
                return
            }

            this.showComponent = 'list-chat-participants'
        }
    },
}
</script>