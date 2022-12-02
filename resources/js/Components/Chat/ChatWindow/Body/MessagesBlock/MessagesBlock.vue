<template>
    <div class="space-y-3 mx-2 relative overflow-x-hidden">
        <SameUserMessageBlock 
            v-for="(block, index) in blocks" 
            :key="index"
            :block="block"
        />
        <ParticipantsTyping :key="'typing'" />
    </div>
</template>

<script>
import ParticipantsTyping from "@/Components/Chat/ChatWindow/Body/MessagesBlock/ParticipantsTyping.vue"
import SameUserMessageBlock from '@/Components/Chat/ChatWindow/Body/MessagesBlock/SameUserMessageBlock.vue'

export default {
    inject: ['group_id'],

    components: { ParticipantsTyping, SameUserMessageBlock, },

    data() {
        return {
            blocks: [], 
            block: {},

            fiveMinsMS: 5 * 60 * 1000
        }
    },

    computed: {
        messages(){ 
            return this.$store.getters[ ns.groupModule(this.group_id, 'messagesM/messages') ]
        },

        whoSawWhat(){ 
            return this.$store.getters[ ns.groupModule(this.group_id, 'messagesM/whoSawWhat') ]
        },
    },

    created(){
        this.createOrUpdateBlocks()
    },

    watch: {
        messages: {
            handler: function () {
                this.createOrUpdateBlocks()
            },
            deep: true,
        },

        whoSawWhat: {
            handler: function () {
                this.createOrUpdateBlocks()
            },
            deep: true,
        },
    }, 

    methods: {
        /**
         * @todo There is no need to recreate blocks on every new message pull/delete
         * 
         * Tryed to refactor this to be statefull but I failed. 
         * This must be implemented because rest of code will fail at some point, one way or the other.
         * 
         * for example animations on get older messages is executed for all blocks even tho they are already in DOM...
         * not to mention MomentsAgo nonsece and its timeouts. 
         */
        createOrUpdateBlocks(){
            // Reset all blocks before updating
            this.blocks = []
            if(this.isObjEmpty(this.messages)) return 

            let ids = this.getAllIds()

            for(let i = 0; i < ids.length; i++){
                let message = this.messages[ids[i]]

                if(this.isObjEmpty(this.block)){
                    this.createNewBlock(message)
                } else {
                    if(this.areSameUsers(message)){
                        this.updateBlock(message)
                    } else {
                        this.addBlockToContainer()
                        this.createNewBlock(message)
                    }
                }

                if(ids[i + 1] == undefined) this.addBlockToContainer()
            }
            
            this.block = {}

            this.makeTimeline()

            // @todo add check if this is testing env
            this.testCheckIfAllMessagesArePlacedInCollector()
        },

        areSameUsers(message){ return this.block.blockOwnerId == message.user_id },

        updateBlock(message){
            this.block.messages.push(message.id)
        },

        addBlockToContainer(){
            this.blocks.push(this.block)
        },

        createNewBlock(message){
            this.block = this.getFreshBlockCollector()
            this.block.messages.push(message.id)
            this.block.blockOwnerId = message.user_id
        },

        getFreshBlockCollector(){
            return {
                messages: [], // array of message ids to be shown in block
                blockOwnerId: null, // id of user which sent these messages
                showTime: [], // array of message ids which will have Moments ago
            }
        },

        /**
         * Since messages are dictionary, get all keys(returns strings then convert to int)
         */
        getAllIds(){
            let ids = Object.keys(this.messages)

            if(ids.length == 0) return []

            return ids.map(id => {
                return Number(id)
            })
        },

        isObjEmpty(o){ return _.isEmpty(o) },

        testCheckIfAllMessagesArePlacedInCollector(){
            let notCollectedIds = []
            let msgIds = this.getAllIds()

            for(let i = 0; i < msgIds.length; i++){
                let collected = false

                for(let j = 0; j < this.blocks.length; j++){
                    
                    if(this.blocks[j].messages.includes(msgIds[i])){
                        collected = true
                        break
                    }
                }

                if(!collected){
                    notCollectedIds.push(msgIds[i])
                }
            }

            if(notCollectedIds.length != 0){
                console.log('Test: testCheckIfAllMessagesArePlacedInCollector:')
                console.log('there are messages which havent been placed in collector')
                console.log(notCollectedIds)
            }
        },

        /**
         * For each block, add "showTime" property which determines if message should dispaly MomentsAgo
         * 
         * It will always give true to first or last last message in block. So if there is only one message, it will show time ago
         */
        makeTimeline(){
            for(let i = 0; i < this.blocks.length; i++){
                let block = this.blocks[i]

                let j = 0

                for(let j = 0; j < block.messages.length; j++){
                    if(block.messages[j + 1] != undefined){
                        if(
                            this.showTimeForCurrentRelativeToNextMessage(
                                this.messages[ block.messages[j    ]].created_at, 
                                this.messages[ block.messages[j + 1]].created_at 
                            )
                        ) this.blocks[i].showTime.push( this.messages[block.messages[j]].id )

                    } else {
                        // if this is last message in block, show time
                        this.blocks[i].showTime.push( this.messages[block.messages[j]].id )
                    }

                }
            }
        },

        /**
         * For two dates where current < next == true, 
         * datermine if they are close enough together to not display time for current
         * 
         * @todo difference for older messages should have lesser tolerance for determining to show or not
         *  currently it only uses 4 minutes as tolerance
         * 
         * @param {Date} current 
         * @param {Date} next 
         */
         showTimeForCurrentRelativeToNextMessage(current, next){
            let currentMS = (new Date(current)).getTime()
            let nextMS    = (new Date(next)).getTime()

            let msDifference = nextMS - currentMS

            return msDifference > this.fiveMinsMS
        },

    },

}

</script>

<style scoped>
    .list-move,
    .list-enter-active,
    .list-leave-active{
        transition: all 0.2s ease-out;
    }
    
    .list-enter-from,
    .list-leave-to{
        opacity: 0;
        transform: scaleY(0.1) translateX(-40px);
    }
    
    .list-leave-active {
        position: absolute;
        width: 100%;
    }
</style>