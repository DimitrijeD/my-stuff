<template>
    <div class="w-full m-auto truncate text-gray-500 dark:text-gray-200">
        <div class="ml-2">
            <ListChatParticipants 
                v-if="showComponent == 'list-chat-participants'"
                :participants="group.participants"
            />
            
            <GroupName 
                v-if="showComponent == 'group-name'"
                :group_name="group.name"
            />

            <div class="cw-head-text" v-if="showComponent == 'default-show'">{{ defaultText }}</div>
            <div class="cw-head-text" v-if="showComponent == 'many-user-text'">{{ manyUserText }}</div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import ListChatParticipants from "@/Components/Chat/ChatWindow/Header/ChatParticipants/ListChatParticipants.vue";
import GroupName from "@/Components/Chat/ChatWindow/Header/ChatParticipants/GroupName.vue";
import * as ns from '@/Store/module_namespaces.js'

export default {
    props:[
        'group',
    ],

    components: {
        ListChatParticipants,
        GroupName,
    },

    data(){
        return{
            showComponent: null,
            defaultText: "A Quiet Place",
        }
    },

    created() {
        this.whatToShowInHeader()
    },

    mounted() {
    
    },

    computed: {
        ...mapGetters({ user: "user" }),

        manyUserText(){
            return `Group with ${Object.keys(this.group.participants).length} participants`
        }
    },

    watch: {
        // these 2 watchers make sure header content is reset when participants or group name are changed
        'group.participants': function (){
            this.whatToShowInHeader()
        },

        'group.name': function (){
            this.whatToShowInHeader()
        },
        // -----------------------------------------------------------------------------------------------
    },

    methods:{
        /**
         * Determine what content should be displayed in Chat Window Header
         * 
         * List of users or group name.
         */
        whatToShowInHeader(){
            if(this.group.name){
                this.showComponent = 'group-name'
                return
            } 

            if(this.group.model_type == "PRIVATE"){
                this.showComponent = 'list-chat-participants'
                return
            }

            const numParticipants = Object.keys(this.group.participants).length

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