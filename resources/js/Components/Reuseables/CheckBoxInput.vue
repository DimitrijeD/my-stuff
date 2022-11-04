<template>
    <div  @click="onInput" >
        <component :is="show" class="def-trans" :class="[boxCls ? boxCls : 'fill-blue-500 dark:fill-blue-500/80']" /> 
    </div>
</template>

<script>
import CheckBoxCheckIcon from '@/Components/Reuseables/Icons/CheckBoxCheckIcon.vue'
import CheckBoxBlancIcon from "@/Components/Reuseables/Icons/CheckBoxBlancIcon.vue"

export default {
    props: [ 'name', 'value', 'boxCls' ],

    components: { CheckBoxCheckIcon, CheckBoxBlancIcon },

    data(){
        return {
            currentVal: this.value,
        }
    },

    computed: {
        show(){
            return this.currentVal
                ? 'CheckBoxCheckIcon' 
                : 'CheckBoxBlancIcon' 
        }
    },

    watch: {
        value(newValue, oldValue){
            this.currentVal = newValue
        }
    },

    methods: {
        onInput() {
            this.currentVal = !this.currentVal

            this.$emit('inputC', {
                key: this.name,
                value: this.currentVal,
            });
        }
    },
}
</script>