<template>
    <div class="space-y-10">
        <h1 class="text-center">Colorz</h1>

        <p class="hover-test-bg-0">Panel for creating global color classes. Configuration which is used for creating classes will be stored in database. If user hasn't modified configuration or hasn't saved any, default will be used to generate classes.</p>
        <!-- <div class="">
            <div class="space-x-2 inline-flex">
                <label class="my-auto">Number of colors - this input should be disabled for global Colorz. </label>
                <input 
                    type="range" 
                    min="1" 
                    max="600"  
                    v-model="shareableConfig.numColors"
                >
                <input 
                    type="text"
                    :value="shareableConfig.numColors"
                    class="flex w-14 p-1 bg-darker-200"
                    @input="setNumColors"
                >
                <span class="my-auto ">{{ shareableConfig.numColors }}</span>
            </div>
        </div> -->
      
        <div class="setting-block">
            <button class="setting-btn-do " @click="updateColorz">Save Colorz Config</button>
            <button class="setting-btn-do " @click="usesDefaultColorz">Default Colorz</button>
            <button class="setting-btn-do " @click="showOnlyBG=!showOnlyBG">
                {{ showOnlyBG ? "Show all colors " : "Show only background" }}
            </button>
        </div>

        <div>
            <div class="slidersWrapper">
                <div class="sliderInputWrapper">
                    <input 
                        type="range" 
                        :min="1" 
                        :max="360" 
                        :value="hueMassOffset"
                        @input="changeSlider($event, 'main')" 
                        class="w-full"
                    >
                </div>
            </div>
        </div>

        <div class="space-y-36"> 
            <div class="space-y-10" v-for="(scheeme, index) in colorz" :key="`${index}${scheeme.name}`">
                <h2 class="">{{ scheeme.scheemeName }}</h2>

                <div class="sliderGridWrapper">
                    <ColorElement 
                        class="sliderRowWrapper"
                        :scheeme="colorScheemas[index].hue"
                        :index="index"
                        :key="`${index}hue`"
                        @changeSlider="changeSlider"
                    />
                    <ColorElement 
                        class="sliderRowWrapper"
                        :scheeme="colorScheemas[index].saturation"
                        :index="index"
                        :key="`${index}saturation`"
                        @changeSlider="changeSlider"
                    />

                    <ColorElement 
                        class="sliderRowWrapper"
                        :scheeme="colorScheemas[index].lightness"
                        :index="index"
                        :key="`${index}lightness`"
                        @changeSlider="changeSlider"
                    />
                </div>

                <div :class="[showOnlyBG ? 'flex' : 'grid grid-cols-4 gap-4 ', ] ">
                    <div class="ex-wrap w-full"  v-if="scheeme?.globalClasses?.backgroundColor">
                        <h3>Background colors</h3>
                        <div :class="['w-full ', showOnlyBG ? 'flex flex-row w-full ' : '']">
                            <div v-for="clr in scheeme.globalClasses.backgroundColor" :class="[clr, 'grow p-4']"></div>
                        </div>
                    </div> 

                    <div class="ex-wrap" v-if="scheeme?.globalClasses?.color && !showOnlyBG">
                        <h3>Text colors</h3>
                        <div v-for="clr in scheeme.globalClasses.color" :class="[clr, 'p-4']">{{ clr }}</div>
                    </div>

                    <div class="ex-wrap" v-if="scheeme?.globalClasses?.borderColor && !showOnlyBG">
                        <h3>Border colors</h3>
                        <div v-for="clr in scheeme.globalClasses.borderColor" :class="[clr, 'border-4 p-2']">{{ clr }}</div>
                    </div>

                    <div class="ex-wrap" v-if="scheeme?.globalClasses?.fill && !showOnlyBG">
                        <h3>SVG fill colors</h3>
                        <div v-for="clr in scheeme.globalClasses.fill" >
                            <AcceptIcon :class="[clr, ' w-10 h-10']" />
                            <span class="p-1">
                                {{ clr }}
                            </span>
                        </div>
                    </div>

                    <div class="ex-wrap" v-if="scheeme?.globalClasses?.stroke && !showOnlyBG">
                        <h3>SVG stroke colors</h3>
                        <div v-for="clr in scheeme.globalClasses.stroke" class="my-4" >
                            <AcceptIcon :class="[clr, ' w-10 h-10']" />
                            <span class="p-1">
                                {{ clr }}
                            </span>
                        </div>
                    </div>

                    <div class="ex-wrap" v-if="scheeme?.globalClasses?.outline  && !showOnlyBG">
                        <h3>Button Outline colors</h3>
                        <button v-for="clr in scheeme.globalClasses.outline" :class="['outline outline-offset-2 p-2 my-6 block rounded', clr]" >
                            {{ clr }}
                        </button>
                    </div>

                    <div class="ex-wrap" v-if="scheeme?.globalClasses?.caret  && !showOnlyBG">
                        <h3>Caret colors</h3>
                        <input v-for="clr in scheeme.globalClasses.caret" :class="['p-2 my-6 block bg-darker-200 text-2xl w-full rounded', clr]" :value="clr">
                    </div>

                    <div class="ex-wrap" v-if="scheeme?.globalClasses?.accent  && !showOnlyBG">
                        <h3>Accent colors</h3>
                        <div v-for="clr in scheeme.globalClasses.accent">
                            <input type="checkbox" :class="['w-6 h-6 my-6 block', clr]" checked>
                            <span>{{ clr }}</span>
                        </div>
                    </div>

                    <div class="ex-wrap" v-if="scheeme?.globalClasses?.textDecorationColor && !showOnlyBG">
                        <h3>Text decoration colors</h3>
                        <div v-for="clr in scheeme.globalClasses.textDecorationColor">
                            <p :class="['my-6 p-2 underline decoration-8', clr]" >{{ clr }}</p>
                        </div>
                    </div>

                    <div class="ex-wrap" v-if="scheeme?.globalClasses?.color  && !showOnlyBG">
                        <h3>Shadow color </h3>
                        <p>- this is a hard one. There are no 'shadow color properties', but if element has shadow and text color, it will use 'color' property for shadow</p>
                        <div v-for="clr in scheeme.globalClasses.color" :class="['p-4 m-10', clr]" style="box-shadow: 10px 10px 10px;"></div>
                    </div> 
                        
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Colorz } from '@/Components/ColorCalculators/Colorz/Colorz.js'
import ColorElement from '@/Components/ColorCalculators/Components/ColorElement.vue';
import AcceptIcon from '@/Components/Reuseables/Icons/AcceptIcon.vue'

import defaultConfig from '@/Pages/Settings/Colorz/types/defaultConfig.js'

// @todo Determine if there is anything to save

export default {
    props: ['user'],

    components: { ColorElement, AcceptIcon, },

    data(){
        return {
            colorScheemas: defaultConfig,
            showOnlyBG: true,
            hueMassOffset: 0,
        }
    },

    computed: {
        colorz(){
            let allRules = ''
            let container = []

            for(let i in this.colorScheemas){
                this.colorScheemas[i].hueMassOffset = this.hueMassOffset
                let cc = new Colorz(this.colorScheemas[i])
                cc.make() 

                allRules += cc.globalRules
                cc.scheemeName = this.colorScheemas[i].name
                container.push(cc)
            }

            Colorz.replaceRules(allRules)

            return container
        },
    },

    created(){
        if(this.user?.user_setting?.colorz){
            this.usesUsersColorz()
        } else {
            this.usesDefaultColorz()
        }
    },

    methods: {
        usesUsersColorz(){
            this.colorScheemas = this.user.user_setting.colorz 
        },

        usesDefaultColorz(){
            this.colorScheemas = defaultConfig
        },

        // setNumColors($event){
        //     let input = Number($event.target.value)
        //     if(Number.isInteger(input) && input > 0 && input < 1000){
        //         this.shareableConfig.numColors = input
        //     }
        // },

        changeSlider(data, type=''){
            if(type == 'main'){
                this.hueMassOffset = parseInt(data.target.value)               
            } else {
                this.colorScheemas[data.index][data.path[0]][data.path[1]] = data.value
            }
        },

        // hexToHSL(H) {
        //     if(!H) return null

        //     // Convert hex to RGB first
        //     let r = 0, g = 0, b = 0;
        //     if (H.length == 4) {
        //         r = "0x" + H[1] + H[1];
        //         g = "0x" + H[2] + H[2];
        //         b = "0x" + H[3] + H[3];
        //     } else if (H.length == 7) {
        //         r = "0x" + H[1] + H[2];
        //         g = "0x" + H[3] + H[4];
        //         b = "0x" + H[5] + H[6];
        //     }

        //     // Then to HSL
        //     r /= 255;
        //     g /= 255;
        //     b /= 255;

        //     let cmin = Math.min(r,g,b),
        //     cmax = Math.max(r,g,b),
        //     delta = cmax - cmin,
        //     h = 0,
        //     s = 0,
        //     l = 0;

        //     if (delta == 0)
        //         h = 0;
        //     else if (cmax == r)
        //         h = ((g - b) / delta) % 6;
        //     else if (cmax == g)
        //         h = (b - r) / delta + 2;
        //     else
        //         h = (r - g) / delta + 4;

        //     h = Math.round(h * 60);

        //     if (h < 0)
        //         h += 360;

        //     l = (cmax + cmin) / 2;
        //     s = delta == 0 ? 0 : delta / (1 - Math.abs(2 * l - 1));
        //     s = +(s * 100).toFixed(1);
        //     l = +(l * 100).toFixed(1);

        //     return {
        //         hue: h,
        //         saturation: s,
        //         lightness: l
        //     };
        // },

        updateColorz(){
            let colorz = []
            for(let i in this.colorScheemas){
                colorz.push(this.colorScheemas[i])
            }

            this.$store.dispatch('updateProfile', {
                settingsFiels: {
                    colorz: colorz
                }
            }).then(() => {
                // this.usesUsersColorz()
            })
        }
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

    .ex-wrap{ @apply bg-gray-400 dark:bg-darker-50 p-2; }
</style>