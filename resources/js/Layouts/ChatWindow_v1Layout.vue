<template>
    <div :class="[ 'def-resize-trans self-end visible flex-none bg-white dark:bg-darker-300 text-gray-600 dark:text-gray-300']" 
        :style="{ height: group.window.minimized ? '' : size.height, width: size.width,  }"
    >
        <div class="flex flex-col overflow-hidden h-full">
            <div class="bg-blue-400 dark:bg-gradient-to-b dark:from-darker-500 dark:via-darker-500 dark:bg-transparent flex flex-nowrap gap-2 h-16"> 
                <slot name="header"></slot>
            </div>

            <div class="h-full flex flex-col border-l border-r border-blue-400 dark:border-none">
                <!-- messages -->
                <div v-show="!group.window.showConfig && !group.window.minimized" class="grow pt-1 flex flex-col ">
                    <div class="h-full relative">
                        <div class="gray absolute top-0 left-0 right-0 bottom-0 overflow-x-hidden scroll1 space-y-2 mb-2 rounded-xl" :ref="scrollName" @scroll="handleScroll($event)">
                            <slot name="messages"></slot>
                        </div>
                        <slot name="action-response"></slot>
                    </div>

                    <slot name="footer"></slot>
                </div>
                <!-- / -->

                <!-- Config -->
                <div v-show="group.window.showConfig && !group.window.minimized" class="grow">
                    <slot name="config"></slot>
                </div>
                <!-- / -->
            </div>
        </div>
    </div>
</template>

<script>
import * as ns from '@/Store/module_namespaces.js'
import { mapGetters } from 'vuex'

export default {
    props: [ 'group', 'size', ],

    data(){
        return {
            scrolledDown: false,
            scrollTopTriggeredOlderMessages: false,
            stickyScrollBot: true,
            position: 0,
            preventConsecutivePullsForOlderMessagesinMS: 3000, // How long to wait until pulling older messages is allowed again
            scrollName: `scroll_${this.group.id}`,
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

        'group.messages': function (newMessages, oldMessages){
            this.position = this.getScrollTipPositionOffsetFromBottom()
            this.$nextTick(()=> {
                this.scrollDownOnEvents()

            })
        },

        /**
         * Scroll down only when other people see messages (if sticky is active)
         * 
         * Does it by comparing old and new seen state, if current user was shifted from one messageId to another, then
         * it will not call scroll event
         */
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
        }

    },

    mounted(){
        this.position = this.getScrollTipPositionOffsetFromBottom()

        if(this.group.window.scrolledDownInitialy){
            this.$nextTick(()=> {
                this.handleInitialMessagesLoad()
            })
        }
    },

    methods: {
        handleScroll(e) {
            this.position = this.getScrollTipPositionOffsetFromBottom()

            this.shouldFixScrollBot()
            this.shouldPullOlderMessages()
        },

        shouldPullOlderMessages(){
            if(this.getScrollTop() < 400 && this.scrolledDown && !this.scrollTopTriggeredOlderMessages){ 
                this.scrollTopTriggeredOlderMessages = true
                this.$store.dispatch(ns.groupModule(this.group.id, 'getEarliestMessages')).then(() => {
                    this.preventFetchingOlderMessagesViaTimeout()
                    this.maintainScrollInCurrentViewport()
                })
            }
        },

        maintainScrollInCurrentViewport(){
            this.$refs.scroll?.scrollTo({
                top: this.getScrollHeight() - this.position - this.getScrollOffsetHeight(),
            })

            this.position = this.getScrollTipPositionOffsetFromBottom()
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
            if(this.stickyScrollBot) this.scrollDownSmooth() 
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

        getScrollTipPositionOffsetFromBottom(){
            return this.getScrollHeight() - this.getScrollTop() - this.getScrollOffsetHeight()
        },

        getScrollTop()         { return this.getScrollEl()?.scrollTop },
        getScrollHeight()      { return this.getScrollEl()?.scrollHeight },
        getScrollOffsetHeight(){ return this.getScrollEl()?.offsetHeight },

        getScrollEl(){ 
            return this.$refs[this.scrollName]
         },

        /**
         * If user scrolls up 200px or more, new events will not force scroll down
         * if user scrolls below 200 px, scroll will go down on every new content
         */
        shouldFixScrollBot(){ this.stickyScrollBot = this.getScrollTipPositionOffsetFromBottom() < 200 },


    },


}

</script>