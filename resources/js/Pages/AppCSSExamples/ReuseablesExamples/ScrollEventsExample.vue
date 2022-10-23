<template>
    <div class="bg-black space-y-10">
        <h1>Fucking scroll</h1>
        <p>U need to stay exactly where you are, no matter what content gets loaded or when then its easy to implement fix points when to 
            ignore and which rules to ignore (like snapping, being fixed to bot/ top stuff like that).
            But this problem is hard for no reason other than who ever created scroll has no fucking brain cells, so I have to deal with this shit and type text which is nobody ever gonna read. tnx
        </p>
        <div class="grid grid-cols-2 gap-8">
            <div class="h-96 scroll1 bg-gray-900" 
                ref="scroll" 
                @scroll="scrolled()"
            >
                <p class="h-16 m-4 bg-blue-800 text-center">
                    Last Element
                </p>
                <p v-for="(stuff, index) in content" :key="index" class="h-16 m-4 bg-neutral-800 text-center">
                    {{stuff}}
                </p>
            </div>

            <div class="flex flex-col ">
                <div class="grid grid-cols-2 gap-4">
                    <button class="p-4 text-center bg-neutral-700 rounded" @click="addContentToStartOfArray()">
                        Add Content On Start of content (Instantly)
                    </button>

                    <button class="p-4 text-center bg-neutral-700 rounded" @click="addContentToStartOfArrayAsync()">
                        Add Content On Start of content( Async 2s Delay)
                    </button>
                </div>
                
                <div class="mt-auto grid grid-cols-2 gap-4">
                    <button class="p-4 text-center bg-neutral-700 rounded" @click="addContentToEndOfArray()">
                        Add Content On End of content (Instantly)
                    </button>

                    <button class="p-4 text-center bg-neutral-700 rounded" @click="addContentToEndOfArrayAsync()">
                        Add Content On End of content (Async 2s Delay)
                    </button>
                </div>
            </div>
        </div>

        <div>
            <h2>Isues</h2>
            <p>Calling <span class="font-bold">Async End</span> then calling <span class="font-bold">Async Start</span> before <span class="font-bold">Async End</span> has finished will change viewport</p>
            <p>Holding scroll, scrolling top, messages are rendered, viewport pushes where user is holding his scroll...</p>
        </div>
    </div>
</template>

<script>


export default{
    data(){
        return {
            content: ['a','b','c','d','e','f','g',],
            dec:0,
            inc:1,
            lastScrollDirection: 'top',
        }
    },

    watch: {
        lastScrollDirection(newDirection, oldDirection){
            this.position = this.getScrollTipPositionOffsetFromBottom()
        }
    },

    mounted(){
        this.position = this.getScrollTipPositionOffsetFromBottom()
    },

    methods: {

        addContentToStartOfArray(){
            this.lastScrollDirection = 'top'

            this.content.unshift(this.dec--)

            this.maintainScrollInCurrentViewport()
        },

        addContentToStartOfArrayAsync(){
            this.lastScrollDirection = 'top'

            setTimeout(()=>{
                this.content.unshift('some content pulled async')
                this.$nextTick(() => {
                    this.$refs.scroll.scrollTo({
                        top: this.getScrollHeight() - this.position - this.getScrollOffsetHeight(),
                    })

                    this.position = this.getScrollTipPositionOffsetFromBottom()
                })
            }, 1000)
        },

        addContentToEndOfArray(){
            this.lastScrollDirection = 'bot'

            this.content.push(this.inc++)

            // position needs to be updated
            this.position = this.getScrollTipPositionOffsetFromBottom()
        },

        addContentToEndOfArrayAsync(){
            this.lastScrollDirection = 'bot'

            new Promise(() => {
                setTimeout(() => {
                    this.content.push('some content pulled async')
                }, 1000);
            }).then(()=>{
                this.position = this.getScrollTipPositionOffsetFromBottom()
            })
        },

        maintainScrollInCurrentViewport(){
            this.$nextTick(() => {
                this.scrollToCurrentView()
            })
        },

        scrollToCurrentView(){
            this.$refs.scroll.scrollTo({
                top: this.getScrollHeight() - this.position - this.getScrollOffsetHeight(),
            })

            this.position = this.getScrollTipPositionOffsetFromBottom()
        },

        scrolled(){
            this.position = this.getScrollTipPositionOffsetFromBottom()
        },

        getScrollTipPositionOffsetFromBottom(){
            return this.getScrollHeight() - this.getScrollTop() - this.getScrollOffsetHeight()
        },

        getScrollTop()         { return this.getScrollEl().scrollTop    },
        getScrollHeight()      { return this.getScrollEl().scrollHeight },
        getScrollOffsetHeight(){ return this.getScrollEl().offsetHeight },

        getScrollEl(){ return this.$refs.scroll },

    }
}
</script>