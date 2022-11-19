<template>
    <TransitionGroup tag="div" name="list" class="space-y-3 mx-2 relative">
        <SameUserMessageBlock 
            v-for="(block, index) in blocks" 
            :key="index"
            :block="block"
        />
        <ParticipantsTyping :key="'typing'" />
    </TransitionGroup>
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
        }
    },

    computed: {
        seen(){ 
            return this.$store.getters[ ns.groupModule(this.group_id, 'seen') ]
        },

        messages(){ 
            return this.$store.getters[ ns.groupModule(this.group_id, 'messages') ]
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
    }, 

    methods: {
        createOrUpdateBlocks(){
            if(this.isObjEmpty(this.messages)) return 

            this.blocks = []
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
                messages: [],
                blockOwnerId: null,
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
        transform: scaleY(0.01) translateX(-40px);
    }
    
    .list-leave-active {
        position: absolute;
        width: 100%;
    }
</style>