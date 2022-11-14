<template>
    <div :ref="scrollName" class="h-full overflow-x-hidden absolute top-0 left-0 right-0 bottom-0 mb-2 rounded-xl scroll1 space-y-2" @scroll="handleScroll($event)" >
        <slot name="messages"></slot>
    </div>
</template>

<script>
import * as ns from '@/Store/module_namespaces.js'
import { mapGetters } from 'vuex'

export default {
    props: [ 'group', ],

    components: {},

    data(){
        return {
            scrollName: `scroll_${this.group.id}`,
            scrolledDown: false,
            scrollTopTriggeredOlderMessages: false,
            stickyBot: true,
            position: 0,
            preventConsecutivePullsForOlderMessagesinMS: 2000, // How long to wait until pulling older messages is allowed again
            throttle: null,
            minDistanceToGetOldMessages: 400,
            maxDistanceFromBottomToFix: 200,
            throttleMS: 60,
        }
    },

    computed: {
        ...mapGetters({ 
            user: "user",
        }),
    },

    watch: {
        'group.window.scrolledDownInitialy': function (){
            if(this.group.window.scrolledDownInitialy){
                this.$nextTick(()=> {
                    this.handleInitialMessagesLoad()
                })
            }
        },

        'group.messages': function (){
            this.position = this.getPosition()
            this.$nextTick(()=> {
                this.scrollDownOnEvents()

            })
        },

        'group.whoSawWhat': {
            deep: true,
            handler(newState, oldState){
                for(let i in oldState){
                    if(oldState[i].includes(this.user.id)){
                        // if that key (message id) exist .Key not exist (be deleted) coz nobody has 'seen' on that message
                        // and if user self is not 'seen' on that message,
                        // meaning this event is called after he saw somebody elses message, but this 
                        // watcher picked up change.
                        // Last check is to be consistent - dont scroll down, if initial load hasn't been executed
                        if(newState.hasOwnProperty(i) && newState[i].includes(this.user.id) && this.scrolledDown){
                            this.$nextTick(()=> {
                                this.scrollDownOnEvents()
                            })
                        }
                        // break coz there is nothing else to do
                        break;
                    }
                }
            },
        },

    },

    mounted(){

        this.position = this.getPosition()

        if(this.group.window.scrolledDownInitialy){
            this.$nextTick(()=> {
                this.handleInitialMessagesLoad()
            })
        }
    },

    methods: {
        handleScroll(e) {
            this.position = this.getPosition()

            if(this.throttle) return

            this.throttle = setTimeout(()=> {
                this.throttle = null
            }, this.throttleMS)

            this.shouldFixBot()
            this.shouldPullOlderMessages()
        },

        shouldPullOlderMessages(){
            if(this.getScrollTop() < this.minDistanceToGetOldMessages && this.scrolledDown && !this.scrollTopTriggeredOlderMessages){ 
                this.scrollTopTriggeredOlderMessages = true
                this.$store.dispatch(ns.groupModule(this.group.id, 'getEarliestMessages')).then(() => {
                    this.preventFetchingOlderMessagesViaTimeout()
                    this.maintainScrollInCurrentViewport()
                })
            }
        },

        maintainScrollInCurrentViewport(){
            this.getScrollEl()?.scrollTo({
                top: this.getScrollHeight() - this.position - this.getScrollOffsetHeight(),
            })

            this.position = this.getPosition()
        },

        handleInitialMessagesLoad(){
            if(Object.keys(this.group.messages).length == 0) return

            this.scrollDown()

            setTimeout(() => {
                this.scrolledDown = true
            }, 3000)
            
            this.$nextTick(()=> {
                this.$store.dispatch(ns.groupModule(this.group.id, 'scrolledDownInitialy'), false)
            })
        },

        scrollDownOnEvents(){ 
            if(this.stickyBot) this.scrollDownSmooth() 
        },

        scrollToPosition(position){
            this.getScrollEl()?.scrollTo({
                top: position ? position : this.getScrollTop(),
            })
        },

        scrollDown(){
            this.getScrollEl()?.scrollTo({
                top: this.getScrollHeight(),
            })
        },

        scrollDownSmooth(){
            this.getScrollEl()?.scrollTo({
                top: this.getScrollHeight(),
                behavior: 'smooth'
            })
        },

        preventFetchingOlderMessagesViaTimeout(){
            setTimeout(() => {
                this.scrollTopTriggeredOlderMessages = false
            }, this.preventConsecutivePullsForOlderMessagesinMS) 
        },

        getPosition(){
            return this.getScrollHeight() - this.getScrollTop() - this.getScrollOffsetHeight()
        },

        getScrollTop()         { return this.getScrollEl()?.scrollTop },
        getScrollHeight()      { return this.getScrollEl()?.scrollHeight },
        getScrollOffsetHeight(){ return this.getScrollEl()?.offsetHeight },

        getScrollEl(){ 
            return this.$refs[this.scrollName]
         },
         
        /**
         * If user scrolls up this.maxDistanceFromBottomToFix or more, new events will not force scroll down
         * if user scrolls below, scroll will go down on every new content
         */
        shouldFixBot(){ this.stickyBot = this.getPosition() < this.maxDistanceFromBottomToFix },
    }
}
</script>