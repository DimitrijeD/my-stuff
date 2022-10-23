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

            blocks: []
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

            let blockCollector = this.getFreshBlockCollector()

            let blockOwnerId = this.group.messages[msgIds[0]].user_id

            for(let i in msgIds){
                if(this.group.messages[msgIds[i]].user_id == blockOwnerId){
                    blockCollector.messages.push(this.group.messages[msgIds[i]])
                    blockCollector.blockOwnerId = this.group.messages[msgIds[i]].user_id
                } else {
                    this.blocks.push(blockCollector)
                    blockCollector = this.getFreshBlockCollector()
                    blockCollector.messages.push(this.group.messages[msgIds[i]])
                    blockOwnerId = this.group.messages[msgIds[i]].user_id
                    blockCollector.blockOwnerId = this.group.messages[msgIds[i]].user_id
                }
            }

            // add last collected block if not empty
            if(blockCollector.messages.length != 0) this.blocks.push(blockCollector)

        },

        getFreshBlockCollector(){
            return {
                messages: [],
                blockOwnerId: null
            }
        },

    },

}

</script>