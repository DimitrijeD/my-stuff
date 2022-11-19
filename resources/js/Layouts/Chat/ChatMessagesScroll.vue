<template>
    <div :ref="scrollName" class="h-full overflow-x-hidden absolute top-0 left-0 right-0 bottom-0 mb-2 rounded-xl scroll1 space-y-2" @scroll="handleScroll($event)" >
        <slot name="messages"></slot>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    inject: ['group_id'],

    components: {},

    data(){
        return {
            scrollName: `scroll_${this.group_id}`,
            scrolledDown: false,
            awaitingOldMessages: false, // boolean which is true only while awaiting fetch for older messages, false otherwise
            stickyBot: true,
            position: 0,
            throttleId: null,
            canPullOld: false,
            activeFutureCallForOld: false, 
            preventApiId: null,

            config: {
                throttleMS: 60,
                autoFixPX: 200,
                fromTopGetOldPX: 400,
                canPullOldOnlyAfterCreatedMS: 1000
            }
        }
    },

    computed: {
        ...mapGetters({ 
            user_id: "user_id",
        }),

        messages_tracker(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'messages_tracker') ]
        },

        window(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'window') ]
        },

        numberOfMessages(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'numberOfMessages') ]
        },

        whoSawWhat(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'whoSawWhat') ]
        },

        usersTyping(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'usersTyping') ]
        },
    },

    watch: {
        'window.scrolledDownInitialy': function (){
            if(this.window.scrolledDownInitialy){
                this.$nextTick(()=> {
                    this.handleInitialMessagesLoad()
                })
            }
        },

        numberOfMessages(){
            this.position = this.getPosition()
            this.$nextTick(()=> {
                this.scrollDownOnEvents()

            })
        },

        whoSawWhat: {
            deep: true,
            handler(newState, oldState){
                for(let i in oldState){
                    if(oldState[i].includes(this.user_id)){
                        // if user self not 'seen' on that message, meaning this event is called after he saw somebody elses message, but this watcher picked up change.
                        // Last check is to be consistent - dont scroll down, if initial load hasn't been executed
                        if(newState.hasOwnProperty(i) && newState[i].includes(this.user_id) && this.scrolledDown){
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

        usersTyping: {
            deep: true,
            handler(){
                this.position = this.getPosition()
                this.$nextTick(()=> {
                    this.scrollDownOnEvents()

                })
            },
        }
    },

    mounted(){
        setTimeout(() => {
            this.canPullOld = true
        }, this.canPullOldOnlyAfterCreatedMS);

        this.position = this.getPosition()

        if(this.window.scrolledDownInitialy){
            this.$nextTick(()=> {
                this.handleInitialMessagesLoad()
            })
        }
    },

    methods: {
        handleScroll() {
            this.position = this.getPosition()
            
            if(this.throttleId) return

            this.throttleId = setTimeout(()=> {
                this.throttleId = null
            }, this.config.throttleMS)

            this.shouldFixBot()

            this.validatePullStatusOnOlderMessages()
        },

        /**
         * During scroll top user reaches breakpoint on which API is triggered, fetching older messages
         */
        validatePullStatusOnOlderMessages(){
            if(!this.canPullOld) return
            if(!this.scrolledDown) return
            if(!this.hasScrollReachedAreaToPullOlderMessages()) return

            if(!this.awaitingOldMessages) 
                this.getOlderMessages()
        },

        /**
         * @todo
         */
        isAlreadyTopWhileAwaitingApi(){
            // if(!this.hasScrollReachedAreaToPullOlderMessages()) return

            // clearInterval(this.preventApiId)
            // this.awaitingOldMessages = false

            // this.getOlderMessages()
        },

        /**
         * Fetches older messages from server, then sets cooldown for API and fixes scroll in same relative position to content it had before new messages
         * added height to scrollable area 
         * 
         */
        getOlderMessages(){
            this.awaitingOldMessages = true
            this.$store.dispatch(ns.groupModule(this.group_id, 'getOlderMessages')).then(() => {
                this.awaitingOldMessages = false
                this.maintainScrollInCurrentViewport()
            })
        },

        /**
         * Is viewport close enough to top of scrollable area in order to fetch older messages
         */
        hasScrollReachedAreaToPullOlderMessages(){
            return this.getScrollTop() < this.config.fromTopGetOldPX
        },

        /**
         * This solved issue: moved viewport when adding new messages on start of scrollable area.
         */
        maintainScrollInCurrentViewport(){
            this.getScrollEl()?.scrollTo({
                top: this.getScrollHeight() - this.position - this.getScrollOffsetHeight(),
            })

            this.position = this.getPosition()
        },

        /**
         * Called only once pre initial chat load in order to make sure viewport is always on bottom of chat window
         * where user spends most of the time (looking at latest message/s).
         */
        handleInitialMessagesLoad(){
            if(this.numberOfMessages == 0) return

            this.scrollDown()

            setTimeout(() => {
                this.scrolledDown = true
            }, 3000)
            
            this.$nextTick(()=> {
                this.$store.dispatch(ns.groupModule(this.group_id, 'scrolledDownInitialy'), false)
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

        /**
         * This formula calculates distance in px from bottom of scrollable area
         */
        getPosition(){ return this.getScrollHeight() - this.getScrollTop() - this.getScrollOffsetHeight() },

        /**
         * Distance in px from top to of scrollable area thumb.
         */
        getScrollTop(){ return this.getScrollEl()?.scrollTop },

        /**
         * Distance in px from top to bottom of scrollable area.
         */
        getScrollHeight(){ return this.getScrollEl()?.scrollHeight },

        /**
         * Height of viewport
         */
        getScrollOffsetHeight(){ return this.getScrollEl()?.offsetHeight },

        /**
         * Ref to scrollable area
         */
        getScrollEl(){ return this.$refs[this.scrollName] },
         
        /**
         * If user scrolls close enough to bottom, viewport will be fixed to lowest point
         */
        shouldFixBot(){ this.stickyBot = this.getPosition() < this.config.autoFixPX },
    }
}
</script>