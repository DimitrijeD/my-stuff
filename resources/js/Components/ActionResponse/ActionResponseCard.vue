<template>
    <div @mouseenter="mouseEnter()" @mouseleave="mouseLeave()" :class="['overflow-hidden mx-auto visible', colors.wrapShadow, mouseOver ? 'brightness-100' : 'brightness-95',  ]" >
        <div :class="[' grow flex flex-col h-full rounded border ', colors.wrap]">
            <div class="flex flex-row place-items-center p-1 space-x-2 overflow-hidden h-full">
                <component :is="iconComp" @click="acknowledged()" :class="[ 'cursor-pointer flex-none opacity-80 w-14 h-14 hover:scale-110 ', mouseOver ? colors.iconMouseIn : colors.iconMouseOut]" />

                <div @click="preventDie()" class="flex flex-col p-2 h-full w-full scroll1 break-words" :class="[ 'flex  pb-1', mouseOver ? colors.txtMouseIn : colors.txtMouseOut ]">
                    <p v-for="(message, index1) in response.messages" class="my-auto pb-2" :key="index1">
                        <span v-for="(mess, index2) in message" :key="index2">
                            {{ mess }}
                        </span>
                    </p>
                </div>
            </div>

            <div v-if="dieAfter" ref="slide" :class="[ ' h-1.5 rounded bg-white slideAnimation', mouseOver ? colors.slideMouseIn : colors.slideMouseOut, ]" ></div>
        </div>
    </div>
</template>

<script>
import DoneIcon from "@/Components/Reuseables/Icons/DoneIcon.vue"
import DeleteIcon from "@/Components/Reuseables/Icons/DeleteIcon.vue"
import InfoIcon from "@/Components/Reuseables/Icons/InfoIcon.vue"
import DefaultResponseIcon from "@/Components/Reuseables/Icons/NumbersIcon.vue"


export default {
    props: [ 
        'response', // required,  response object with type and message text
        'dieAfter',  //  not required int, after how many seconds should message die, if ommited it wont die until user closes it
        'colors'
    ],

    components: { DoneIcon, DeleteIcon, InfoIcon, DefaultResponseIcon },

    data(){
        return {
            mouseOver: false,
            sliderDuration: this.dieAfter ? ''.concat(this.dieAfter) + 's' : '',
            timeoutRemaining: null, 
            timeoutEnd: null, 
            timeoutId: null,
            preventedDie: false
        }
    },

    computed: {
        iconComp(){
            if(this.response.response_type == 'success') return 'DoneIcon'
            if(this.response.response_type == 'error') return 'DeleteIcon'
            if(this.response.response_type == 'info') return 'InfoIcon'
            return 'DefaultResponseIcon'
        },
    },

    mounted(){
        if(this.dieAfter) { this.startTimeout() }

    },

    methods: {
        preventDie(){
            if(this.dieAfter) { 
                this.preventedDie = true
                this.offSlide()
            }
        },

        acknowledged(){
            this.$emit('acknowledged', this.response.id)
        },

        mouseEnter(){
            this.mouseOver = true

            if(this.dieAfter && !this.preventedDie) { 
                this.pauseTimeout()

                this.toggleSlide()
             }
        },

        mouseLeave(){
            this.mouseOver = false

            if(this.dieAfter && !this.preventedDie) { 
                this.resumeTimeout() 

                this.toggleSlide()
            }
        },

        toggleSlide(){
            if(!this.$refs.slide) return

            if(this.$refs.slide.style.animationPlayState == 'paused'){
                this.$refs.slide.style.animationPlayState = 'running'
            } else {
                this.$refs.slide.style.animationPlayState = 'paused'
            }
        },

        offSlide(){
            if(!this.$refs.slide) return

            this.$refs.slide.style.animationPlayState = 'paused'
        },

        startTimeout(){
            this.timeoutRemaining = 1000 * this.dieAfter
            this.timeoutEnd = Date.now() + this.timeoutRemaining

            this.dieTimeout()
        },

        pauseTimeout(){
            this.timeoutRemaining = this.timeoutEnd - Date.now()

            clearTimeout(this.timeoutId)
        },

        resumeTimeout(){
            this.timeoutEnd = Date.now() + this.timeoutRemaining

            this.dieTimeout()
        },

        dieTimeout(){
            this.timeoutId = setTimeout(() =>{
                this.acknowledged()
            }, this.timeoutRemaining)
        },

    }
}

</script>

<style scoped>
.slideAnimation {
    animation-name: slideRight;
    animation-duration: v-bind('sliderDuration');
    animation-timing-function: linear;
    animation-fill-mode: forwards;
}

@keyframes slideRight {
    from { 
        margin-left: 0%; 
        opacity: 0.6;
    }
    to { 
        margin-left: 100%; 
        opacity: 1;

    }
} 

</style>