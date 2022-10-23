<template>
    <div>
        <div class="firstRowWrapper">
            <p class="rowName">
                {{ data.name }}
            </p>
            <button 
                @click="data.overflow = !data.overflow" 
                :class="[data.overflow ? 'overflowBtnActive' : 'overflowBtnInActive']"
            >
                Overflow
            </button>
        </div>

        <div :class="['slidersWrapper']" >
            <label>Start</label>
            <div class="sliderInputWrapper">
                <input 
                    type="range" 
                    :min="data.slider.start.min" 
                    :max="data.slider.start.max" 
                    :value="data.start"
                    @input="changeSlider($event, 'start')" 
                    class="w-full"
                >
            </div>
            <span>{{ data.start }}</span>
        </div>

        <div 
            :class="['slidersWrapper', data.use == 'to' ? '' : 'slidersInactive']" 
            @mousedown="changeUse('use', 'to')"
        >
            <label>End</label>
            <div class="sliderInputWrapper">
                <input 
                    type="range" 
                    :min="data.slider.to.min" 
                    :max="data.slider.to.max"  
                    :value="data.end"
                    @input="changeSlider($event, 'end')" 
                    class="w-full" 
                >
            </div>
            <span>{{ data.end }}</span>
        </div>

        <div 
            :class="['slidersWrapper', data.use == 'inc' ? '' : 'slidersInactive']" 
            @mousedown="changeUse('use', 'inc')"
        >
            <label>Increment</label>
            <div class="sliderInputWrapper">
                <input 
                    type="range" 
                    :min="data.slider.inc.min" 
                    :max="data.slider.inc.max"  
                    :value="data.inc"
                    @input="changeSlider($event, 'inc')" 
                    class="w-full"
                >
            </div>
            <span>{{ data.inc }}</span>
        </div>

        <div 
            :class="['slidersWrapper', data.use == 'dec' ? '' : 'slidersInactive']" 
            @mousedown="changeUse('use', 'dec')"
        >
            <label>Decrement</label>
            <div class="sliderInputWrapper">
                <input 
                    type="range" 
                    :min="data.slider.dec.min" 
                    :max="data.slider.dec.max"  
                    :value="data.dec"
                    @input="changeSlider($event, 'dec')" 
                    class="w-full"
                >
            </div>
            <span>{{ data.dec }}</span>
        </div>
    </div>
</template>

<script>

export default {
    props: ['d'],

    components: {},

    data(){
        return {
            data: this.d, // Initially provided Data
        }
    },

    methods: {
        changeSlider(e, key){
            this.$emit( 'changeSlider', {
                path: [this.data.name, key],
                value: e.target.value
            })
        },

        changeUse(key, value){
            this.$emit( 'changeSlider', {
                path: [this.data.name, key],
                value: value
            })
        }

    }

}
</script>