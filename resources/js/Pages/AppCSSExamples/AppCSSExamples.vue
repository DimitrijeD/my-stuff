<template>
    <div v-if="!currentShow" class="w-full grid grid-cols-2 gap-2">
        <button 
            v-for="component in components"
            class="btn" 
            @click="currentShow = component">
                {{ component }}
        </button>

        <button 
            v-for="component in components"
            class="btn" 
            @click="currentShow = component">
                {{ component }}
        </button>
    </div>
    <div v-else class="w-full">
        <button @click="currentShow = ''" class=" w-16 fill-gray-400 dark:fill-gray-400 hover:fill-gray-500 dark:hover:fill-gray-300 float-right ">
            <BackIcon class="" />
        </button>
        
        <div class="">
            <component :is="currentShow"  />
        </div>
    </div>

</template>

<script>
import BackIcon from "@/Components/Reuseables/Icons/BackIcon.vue"

import AreYouSureLayoutExample from '@/Pages/AppCSSExamples/LayoutExamples/AreYouSureLayoutExample.vue'
import DoubleScrollContentCardLayoutExample from '@/Pages/AppCSSExamples/LayoutExamples/DoubleScrollContentCardLayoutExample.vue'
import FillRemainingSpaceLayoutExample from '@/Pages/AppCSSExamples/LayoutExamples/FillRemainingSpaceLayoutExample.vue'
import DefaultCardLayoutExample from '@/Pages/AppCSSExamples/LayoutExamples/DefaultCardLayoutExample.vue'
import GroupCardLayoutExample from '@/Pages/AppCSSExamples/LayoutExamples/GroupCardLayoutExample.vue'
import ScrollLayoutExample from '@/Pages/AppCSSExamples/LayoutExamples/ScrollLayoutExample.vue'

import HeadingTag from '@/Pages/AppCSSExamples/ReuseablesExamples/HeadingTag.vue'
import IconsExample from '@/Pages/AppCSSExamples/ReuseablesExamples/IconsExample.vue'
import CustomColorsPaletesExample from '@/Pages/AppCSSExamples/ReuseablesExamples/CustomColorsPaletesExample.vue'
import ColorCalculatorExample from '@/Pages/AppCSSExamples/ReuseablesExamples/ColorCalculatorExample.vue'
import ScrollEventsExample from '@/Pages/AppCSSExamples/ReuseablesExamples/ScrollEventsExample.vue'

import MomentsAgoExample from '@/Pages/AppCSSExamples/ReuseablesExamples/MomentsAgoExample.vue'

export default {
    components:{
        AreYouSureLayoutExample,
        DoubleScrollContentCardLayoutExample,
        HeadingTag,
        FillRemainingSpaceLayoutExample,
        BackIcon,
        IconsExample,
        DefaultCardLayoutExample,
        CustomColorsPaletesExample,
        ColorCalculatorExample,
        GroupCardLayoutExample,
        ScrollLayoutExample,
        ScrollEventsExample,
        MomentsAgoExample,
    },

    data(){
        return {
            currentShow: '',
            components: [
                'AreYouSureLayoutExample',
                'DoubleScrollContentCardLayoutExample',
                'HeadingTag',
                'FillRemainingSpaceLayoutExample',
                'IconsExample',
                'DefaultCardLayoutExample',
                'CustomColorsPaletesExample',
                'ColorCalculatorExample',
                'GroupCardLayoutExample',
                'ScrollLayoutExample',
                'ScrollEventsExample',
                'MomentsAgoExample',
            ],
        }
    },

    created(){
        
    },

    methods: {

    },

}

</script>


<style scoped>
.btn {
    @apply border-2 break-words py-10
    text-gray-600 hover:text-gray-700 border-gray-300 hover:border-gray-400
    dark:text-gray-400 dark:border-darker-500 dark:hover:text-gray-300 dark:hover:border-darker-600;
}
</style>