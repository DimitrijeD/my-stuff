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
            <!-- <div v-for="(color, index) in slider" :key="index" :style="color" class="text-center p-2 text-blue-400">{{ color }}</div> -->
            <div class="h-96 overflow-hidden flex flex-col">
                <div v-for="(color, index) in slider" :key="index" :style="[color, ]" class="grow"></div>
            </div>

            <!-- <div class="">
                <div v-for="(color, index) in slider" :key="index" :style="color" class="p-2">{{color}}</div>
            </div> -->

            <div class="">
                <p @click="showCyrcle = !showCyrcle">{{ showCyrcle ? 'Hide' : 'Show'}} Cyrcle</p>
                <div v-if="showCyrcle" class="mx-auto w-96 h-96 relative ">
                    <div v-for="(color, index) in slider" :key="index" :style="[color, getDimCyrcle(384, index)]" :class="[
                        getDimCyrcle(384, index), 
                        'm-auto absolute inset-0 rounded-full']"></div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import * as ns from '@/Store/module_namespaces.js'

import { HSLColorCalculator } from '@/Components/ColorCalculators/HSLColorCalculator/HSLColorCalculator.js'
import ColorElement from '@/Components/ColorCalculators/Components/ColorElement.vue';

export default {
    components: { ColorElement, },

    data(){
        return {
            scheme: {},
            loopIntervalId: null,
            showCyrcle: true,

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
                }
            },

            slider1: {
                scheme: "hsl",
                numColors: 10,
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
            const cc = new HSLColorCalculator(this.slider1)
            cc.make() 

            return cc.classes
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

<style>
    .sliderGridWrapper {
        @apply flex flex-col gap-2 text-sm;
    }

    .sliderRowWrapper {
        @apply flex flex-row h-16 gap-6;
    }

    .firstRowWrapper {
        @apply flex flex-row gap-2;
    }

    .overflowBtnActive {
        @apply text-blue-300  rounded;
    }

    .overflowBtnInActive {
        @apply text-gray-300 opacity-30 rounded;
    }

    .slidersWrapper {
        @apply w-[22%] px-1 rounded flex justify-start gap-2 place-items-center cursor-pointer bg-darker-100;
    }

    .slidersInactive {
        @apply opacity-30 bg-transparent; 
    }

    .sliderInputWrapper {
        @apply w-full;
    }

    .rowName {
        @apply my-auto w-[70px];
    }

</style>