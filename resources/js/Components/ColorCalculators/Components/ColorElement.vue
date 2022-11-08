<template>
    <div>
        <div class="firstRowWrapper">
            <span class="rowName">
                {{ scheeme.name }}
            </span>
            <button @click="scheeme.overflow = !scheeme.overflow" :class="[scheeme.overflow ? 'overflowBtnActive' : 'overflowBtnInActive']">
                Overflow
            </button>
        </div>

        <div :class="['slidersWrapper']"  >
            <label>Start</label>
            <div class="sliderInputWrapper">
                <input 
                    type="range" 
                    :min="scheeme.slider.start.min" 
                    :max="scheeme.slider.start.max" 
                    :value="scheeme.start"
                    @input="changeSlider($event, 'start')" 
                    class="w-full"
                >
            </div>
            <span>{{ scheeme.start }}</span>
        </div>

        <div class="flex items-center" >
            <component 
                @click="lockEndToStart()"
                @mousedown="changeUse('use', 'to')"
                :is="isLocked ? 'LockClosedIcon' : 'LockOpenIcon'" 
                :class="['w-6 stroke-gray-600 dark:stroke-blue-400', isLocked ? '' : 'opacity-20',  scheeme.use == 'to' ? '' : 'slidersInactive']" 
            />
        </div>

        <div :class="['slidersWrapper', scheeme.use == 'to' ? '' : 'slidersInactive']" @mousedown="changeUse('use', 'to')">
            <label>End</label>
            <div class="sliderInputWrapper">
                <input 
                    type="range" 
                    :min="scheeme.slider.to.min" 
                    :max="scheeme.slider.to.max"  
                    :value="scheeme.end"
                    @input="changeSlider($event, 'end')" 
                    class="w-full" 
                >
            </div>
            <span>{{ scheeme.end }}</span>
        </div>

        <div :class="['slidersWrapper', scheeme.use == 'inc' ? '' : 'slidersInactive']" @mousedown="changeUse('use', 'inc')">
            <label>Increment</label>
            <div class="sliderInputWrapper">
                <input 
                    type="range" 
                    :min="scheeme.slider.inc.min" 
                    :max="scheeme.slider.inc.max"  
                    :value="scheeme.inc"
                    @input="changeSlider($event, 'inc')" 
                    class="w-full"
                >
            </div>
            <span>{{ scheeme.inc }}</span>
        </div>

        <div :class="['slidersWrapper', scheeme.use == 'dec' ? '' : 'slidersInactive']" @mousedown="changeUse('use', 'dec')">
            <label>Decrement</label>
            <div class="sliderInputWrapper">
                <input 
                    type="range" 
                    :min="scheeme.slider.dec.min" 
                    :max="scheeme.slider.dec.max"  
                    :value="scheeme.dec"
                    @input="changeSlider($event, 'dec')" 
                    class="w-full"
                >
            </div>
            <span>{{ scheeme.dec }}</span>
        </div>
    </div>
</template>

<script>
import LockOpenIcon from "@/Components/Reuseables/Icons/LockOpenIcon.vue"
import LockClosedIcon from "@/Components/Reuseables/Icons/LockClosedIcon.vue"

export default {
    props: ['scheeme', 'index'],

    components: { LockOpenIcon, LockClosedIcon, },

    data(){
        return {
            isLocked: false,
        }
    },

    methods: {
        /**
         * Changes slider value
         */
        changeSlider(e, key){
            if((key == 'start' || key == 'end') && this.isLocked){
                this.scheeme.start = e.target.value
                this.scheeme.end   = e.target.value

                this.$emit( 'changeSlider', {
                    path: [this.scheeme.name, 'start'],
                    value: e.target.value,
                    index: this.index
                })

                this.$emit( 'changeSlider', {
                    path: [this.scheeme.name, 'end'],
                    value: e.target.value,
                    index: this.index
                })
            } else {

                this.$emit( 'changeSlider', {
                    path: [this.scheeme.name, key],
                    value: e.target.value,
                    index: this.index
                })
            }
        },

        /**
         * Changes which slider is active
         */
        changeUse(key, value){
            this.$emit( 'changeSlider', {
                path: [this.scheeme.name, key],
                value: value,
                index: this.index
            })
        },

        lockEndToStart(){
            this.isLocked = !this.isLocked

            if(this.isLocked) {
                this.scheeme.end = this.scheeme.start
            }

        }

    }

}
</script>

<style scoped>
input[type=text] {
  height: 50px;
}
input[type=text],
input[type=range] {
  -webkit-appearance: none;
  margin: 18px 0;
  /* width: 200px; */
}
input[type=range]:focus {
  outline: none;
}
input[type=range]::-webkit-slider-runnable-track {
  width: 100%;
  height: 8.4px;
  cursor: pointer;
  animate: 0.2s;
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
  background: #3071a9;
  border-radius: 1.3px;
  border: 0.2px solid #010101;
}
input[type=range]::-webkit-slider-thumb {
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
  border: 1px solid #000000;
  height: 36px;
  width: 16px;
  border-radius: 3px;
  background: #ffffff;
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: -14px;
}
input[type=range]:focus::-webkit-slider-runnable-track {
  background: #367ebd;
}
input[type=range]::-moz-range-track {
  width: 100%;
  height: 8.4px;
  cursor: pointer;
  animate: 0.2s;
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
  background: #3071a9;
  border-radius: 1.3px;
  border: 0.2px solid #010101;
}
input[type=range]::-moz-range-thumb {
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
  border: 1px solid #000000;
  height: 36px;
  width: 16px;
  border-radius: 3px;
  background: #ffffff;
  cursor: pointer;
}

  /* CSS is missing in your code  */
input[type=range]::-ms-track {
  width: 100%;
  height: 8.4px;
  cursor: pointer;
  animate: 0.2s;
  background: transparent;
  border-color: transparent;
  border-width: 16px 0;
  color: transparent;
}
input[type=range]::-ms-fill-lower {
  background: #2a6495;
  border: 0.2px solid #010101;
  border-radius: 2.6px;
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
}
input[type=range]::-ms-fill-upper {
  background: #3071a9;
  border: 0.2px solid #010101;
  border-radius: 2.6px;
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
}
input[type=range]::-ms-thumb {
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
  border: 1px solid #000000;
  height: 36px;
  width: 16px;
  border-radius: 3px;
  background: #ffffff;
  cursor: pointer;
}
input[type=range]:focus::-ms-fill-lower {
  background: #3071a9;
}
input[type=range]:focus::-ms-fill-upper {
  background: #367ebd;
}
</style>