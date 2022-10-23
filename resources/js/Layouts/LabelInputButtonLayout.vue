<template>
    <div class="">
        <div class="flex inline-flex gap-2 w-full ">
            <div class=" my-auto">
                <slot name="label"></slot>
            </div>
            <div :class="[getInputWidth, 'grow my-auto ml-auto']" >
                <slot name="input"></slot>
            </div>
            <div class="my-auto">
                <slot name="button"></slot>
            </div>
        </div>
    </div>
</template>
    
<script>

export default {
    props: [ 'inputWidthCls', ],

    data(){
        return{
            defaultInputWidth: 'min-w-[40%]', 
        }
    },

    computed: {
        getInputWidth(){
            return this.inputWidthCls 
                ? this.inputWidthCls
                :this.defaultInputWidth 
        }
    }
}

</script>
    