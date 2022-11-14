<template>
    <div class="flex w-full h-full flex-col overflow-hidden "> 
        <div class="">
            <slot name="header"></slot>
        </div>

        <div class="grow overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 bottom-0 scroll1" :class="[contentGapCls ? contentGapCls : 'space-y-2']">
                <slot name="body"></slot>
            </div>
        </div>

        <div class="">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script>
/*
 * Root element must have fixed height and weigth to determine space of Card
 * 
 * Header doesn't grow 
 * Body grows to fill remaining space
 * Footer doesn't grow
 * 
 */

 export default {
    props: [ 'contentGapCls' ]
 }

</script>
