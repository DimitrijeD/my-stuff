<template>
    <div class="space-y-10">

        <div class="space-y-2">
            <h1 class="">HSL SLIDER</h1>

            <div class="flex flex-col space-y-4">

                <div class="sliderGridWrapper">
                    <ColorElement 
                        class="sliderRowWrapper"
                        :d="slider1.hue"
                        @changeSlider="changeSlider"
                    />

                    <ColorElement 
                        class="sliderRowWrapper"
                        :d="slider1.saturation"
                        @changeSlider="changeSlider"
                    />

                    <ColorElement 
                        class="sliderRowWrapper"
                        :d="slider1.lightness"
                        @changeSlider="changeSlider"
                    />
                </div>

                <div class="">
                    <div class="space-x-2 inline-flex">
                        <label class="my-auto">Number of colors</label>
                        <input 
                            type="range" 
                            min="1" 
                            max="600"  
                            v-model="slider1.numColors"
                        >
                        <input 
                            type="text"
                            :value="slider1.numColors"
                            class="flex w-14 p-1 bg-darker-200"
                            @input="setNumColors"
                        >
                        <span class="my-auto ">{{ slider1.numColors }}</span>
                    </div>
                </div>
                <div class="space-x-2" v-if="canLoop">
                        <button v-if="!loopIntervalId" @click="loopCurrentConfig()" class="p-2 rounded border border-darker-300">
                            Start looping current config
                        </button>
                        <button v-else @click="stopLoop()" class="p-2 rounded border border-darker-300">
                            Stop looping current config
                        </button>
                    </div>
            </div>
        </div>

        <div class="pb-96 space-y-10">
            <!-- <div v-for="(color, index) in slider.classes" :key="index" :style="color" class="text-center p-2 text-blue-400">{{ color }}</div> -->
            <div class="h-96 overflow-hidden flex flex-col">
                <div v-for="(color, index) in slider.classes" :key="index" :style="[color, ]" class="grow"></div>
            </div>

            <!-- <div class="">
                <div v-for="(color, index) in slider.classes" :key="index" :style="color" class="p-2">{{color}}</div>
            </div> -->

            <div class="">
                <p @click="showCyrcle = !showCyrcle">{{ showCyrcle ? 'Hide' : 'Show'}} Cyrcle</p>
                <div v-if="showCyrcle" class="mx-auto w-96 h-96 relative ">
                    <div v-for="(color, index) in slider.classes" :key="index" :style="[color, getDimCyrcle(384, index)]" :class="[
                        getDimCyrcle(384, index), 
                        'm-auto absolute inset-0 rounded-full']"></div>
                </div>
            </div>

            <div>
                <h2>Globally accessible classes</h2>
                <div class="grid grid-cols-4 gap-4 ">
                    <div class="ex-wrap">
                        <h3>Background colors</h3>
                        <div v-for="clr in slider.globalClasses.backgroundColor" :class="[clr, 'p-4']">{{ clr }}</div>
                    </div>

                    <div class="ex-wrap">
                        <h3>Text colors</h3>
                        <div v-for="clr in slider.globalClasses.color" :class="[clr, 'p-4']">{{ clr }}</div>
                    </div>

                    <div class="ex-wrap">
                        <h3>Border colors</h3>
                        <div v-for="clr in slider.globalClasses.borderColor" :class="[clr, 'border-4 p-2']">{{ clr }}</div>
                    </div>

                    <div class="ex-wrap">
                        <h3>SVG fill colors</h3>
                        <div v-for="clr in slider.globalClasses.fill" >
                            <AcceptIcon :class="[clr, ' w-10 h-10']" />
                            <span class="p-1">
                                {{ clr }}
                            </span>
                        </div>
                    </div>

                    <div class="ex-wrap">
                        <h3>SVG stroke colors</h3>
                        <div v-for="clr in slider.globalClasses.stroke" class="my-4" >
                            <AcceptIcon :class="[clr, ' w-10 h-10']" />
                            <span class="p-1">
                                {{ clr }}
                            </span>
                        </div>
                    </div>


                    <div class="ex-wrap">
                        <h3>Button Outline colors</h3>
                        <button v-for="clr in slider.globalClasses.outline" :class="['outline outline-offset-2 p-2 my-6 block rounded', clr]" >
                            {{ clr }}
                        </button>
                    </div>

                    <div class="ex-wrap">
                        <h3>Caret colors</h3>
                        <input v-for="clr in slider.globalClasses.caret" :class="['p-2 my-6 block bg-darker-200 text-2xl w-full rounded', clr]" :value="clr">
                    </div>

                    <div class="ex-wrap">
                        <h3>Accent colors</h3>
                        <div v-for="clr in slider.globalClasses.accent">
                            <input type="checkbox" :class="['w-6 h-6 my-6 block', clr]" checked>
                            <span>{{ clr }}</span>
                        </div>
                    </div>

                    <div class="ex-wrap">
                        <h3>Text decoration colors</h3>
                        <div v-for="clr in slider.globalClasses.textDecorationColor">
                            <p :class="['my-6 p-2 underline decoration-8', clr]" >{{ clr }}</p>
                        </div>
                    </div>

                    <div class="ex-wrap">
                        <h3>Shadow color </h3>
                        <p>- this is a hard one. There are no 'shadow color properties', but if element has shadow and text color, it will use 'color' property for shadow</p>
                        <div v-for="clr in slider.globalClasses.color" :class="['p-4 m-10', clr]" style="box-shadow: 10px 10px 10px;">

                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import { Colorz } from '@/Components/ColorCalculators/Colorz/Colorz.js'
import ColorElement from '@/Components/ColorCalculators/Components/ColorElement.vue';
import AcceptIcon from '@/Components/Reuseables/Icons/AcceptIcon.vue'

export default {
    components: { ColorElement, AcceptIcon, },

    data(){
        return {
            scheme: {},
            loopIntervalId: null,
            showCyrcle: false,

            binder: {
                hue: {
                    active: false,
                    difference: 0
                },

                saturation: {
                    active: false,
                    difference: 0
                },

                lightness: {
                    active: false,
                    difference: 0
                },

            },

            slider1: {
                testing: true,
                makeGlobal: true,
                scheme: "hsl",
                numColors: 10,
                colorz: {
                    backgroundColor: {
                        prefix: 'colorz-bg-',
                        property: ' background-color: '
                    },
                    color: {
                        prefix: 'colorz-text-',
                        property: ' color: '
                    },
                    borderColor: { 
                        prefix: 'colorz-border-',
                        property: ' border-color: '     
                    },
                    fill: {
                        prefix: 'colorz-fill-',
                        property: ' fill: '
                    },
                    stroke: {
                        prefix: 'colorz-stroke-',
                        property: ' stroke: '
                    },
                    outline: {
                        prefix: 'colorz-outline-',
                        property: 'outline-color:'
                    },
                    caret: {
                        prefix: 'colorz-caret-',
                        property: 'caret-color:'
                    },
                    accent: {
                        prefix: 'colorz-accent-',
                        property: 'accent-color:'
                    },
                    textDecorationColor: {
                        prefix: 'colorz-txtdec-',
                        property: 'text-decoration-color:'
                    },
                },
                hue:{
                    name: "hue", 
                    slider: {
                        start: {
                            min: 0,
                            max: 360
                        },
                        to: {
                            min: 0,
                            max: 360
                        },
                        inc:{
                            min: 0,
                            max: 359
                        },
                        dec: {
                            min: 0,
                            max: 359
                        },
                    },
                    boundaries: {
                        min: 0, max: 360
                    },
                    start: 0,
                    end: 360,
                    inc: 0,
                    dec: 0,
                    overflow: false,
                    use: 'to',
                },

                saturation:{
                    name: "saturation", 
                    slider: {
                        start: {
                            min: 0,
                            max: 100
                        },
                        to: {
                            min: 0,
                            max: 100
                        },
                        inc:{
                            min: 0,
                            max: 99
                        },
                        dec: {
                            min: 0,
                            max: 99
                        },
                    },
                    boundaries: {
                        min: 0, max: 100
                    },
                    start: 0,
                    end: 100,
                    inc: 0,
                    dec: 0,
                    overflow: false,
                    use: 'to',
                },

                lightness:{
                    name: "lightness", 
                    slider: {
                        start: {
                            min: 0,
                            max: 100
                        },
                        to: {
                            min: 0,
                            max: 100
                        },
                        inc:{
                            min: 0,
                            max: 99
                        },
                        dec: {
                            min: 0,
                            max: 99
                        },
                    },
                    boundaries: {
                        min: 0, max: 100
                    },
                    start: 0,
                    end: 100,
                    inc: 0,
                    dec: 0,
                    overflow: false,
                    use: 'to',
                },
            },

        }
    },

    computed: {
        slider(){
            const cc = new Colorz(this.slider1)
            cc.make() 

            return cc
        },

        canLoop(){
            if(
                this.slider1.hue.use == 'inc' || this.slider1.hue.use == 'dec'
                || this.slider1.saturation.use == 'inc' || this.slider1.saturation.use == 'dec'
                || this.slider1.lightness.use == 'inc' || this.slider1.lightness.use == 'dec'
            ){
                return true
            }
            this.stopLoop()
            return false
        }
    },

    watch: {
        
    },

    created(){
        
    },

    methods: {
        setNumColors($event){
            let input = Number($event.target.value)
            if(Number.isInteger(input) && input > 0 && input < 1000){
                this.slider1.numColors = input
            }
        },

        getDimCyrcle(max, index){
            const dim = parseInt( max - ((max/Object.keys(this.slider).length) * index) ) 
            return {
                width: dim + 'px',
                height: dim + 'px'
            }
        },

        numColorsChange(num){
            return this.slider1.numColors + num < 0 
                ? null 
                : this.slider1.numColors += num
        },

        changeSlider(data){
            this.slider1[data.path[0]][data.path[1]] = data.value
        },

        loopCurrentConfig(){
            this.loopIntervalId = setInterval(()=>{
                if(this.slider1.hue.use == 'inc') ++this.slider1.hue.inc 

                if(this.slider1.hue.use == 'dec') --this.slider1.hue.dec

                if(this.slider1.saturation.use == 'inc') ++this.slider1.saturation.inc

                if(this.slider1.saturation.use == 'dec') --this.slider1.saturation.dec

                if(this.slider1.lightness.use == 'inc') ++this.slider1.lightness.inc

                if(this.slider1.lightness.use == 'dec') --this.slider1.lightness.dec

            } , 100)
        },

        stopLoop(){
            clearInterval(this.loopIntervalId)
            this.loopIntervalId = null
        },

    }
}
</script>