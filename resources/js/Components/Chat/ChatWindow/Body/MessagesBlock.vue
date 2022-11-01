<template>
    <div class="space-y-2 mx-2">
        <SameUserMessageBlock 
            v-for="(block, index) in blocks" 
            :key="index"
            :data-index="index"
            :block="block"
            :group="group"
            
        />
        <ParticipantsTyping :group_id="group.id" />
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import * as ns from '@/Store/module_namespaces.js'

import ParticipantsTyping from "@/Components/Chat/ChatWindow/Body/ParticipantsTyping.vue";
import SameUserMessageBlock from '@/Components/Chat/ChatWindow/Body/SameUserMessageBlock.vue';

export default {
    props: [ 'group', ],

    components: {
        ParticipantsTyping,
        SameUserMessageBlock,
    },

    data() {
        return {
            gm_ns: ns.groupModule(this.group.id), 

            blocks: [],
        }
    },

    computed: {
        ...mapGetters({ 
            user: "user",
         }),

        seen(){ 
            return this.$store.getters[`${this.gm_ns}/seen`]
        },
    },

    created(){
        this.createBlocks()
    },

    watch: {
        // 'group.messages_tracker.last_message.id': function (newVal, oldVal) {
        //     this.$nextTick(() => {
                
        //     })
        // },

        'group.messages': {
            handler: function () {
                this.createBlocks()
            },
            deep: true,
        },

    }, 

    methods: {
        /**
         * Groups messages that belong to distinct users into array from start to finish
         * 
         * New block is created once iterator finds another user
         * Called uppon creation and every time messages obj changes
         */
        createBlocks(){
            this.blocks = []
            
            if(!Object.keys( this.group.messages).length) return 

            const msgIds = Object.keys( this.group.messages).map(id => {
                return Number(id);
            }).sort((a, b) => {
                return a - b;
            })

            let block = this.getFreshBlockCollector()

            let blockOwnerId = this.group.messages[msgIds[0]].user_id
            let msgId = null
            let message = null

            for(let i = 0; i < msgIds.length; i++){
                msgId = msgIds[i]
                message = this.group.messages[msgId]

                if(message.user_id == blockOwnerId){
                    block.messages.push(message)
                    block.blockOwnerId = message.user_id
                } else {
                    this.blocks.push(block)
                    block = this.getFreshBlockCollector()
                    block.messages.push(message)
                    blockOwnerId = message.user_id
                    block.blockOwnerId = message.user_id
                }
            }

            // add last collected block if not empty
            if(block.messages.length != 0) this.blocks.push(block)
        },

        getFreshBlockCollector(){
            return {
                messages: [],
                blockOwnerId: null,
            }
        },

    },

}

</script>