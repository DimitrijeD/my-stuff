<template>
    <div class="flex flex-col space-y-2 overflow-hidden w-full h-full "> 
        <div class="flex-none">
            <slot name="header"></slot>
        </div>

        <div class="grow">
            <slot name="content"></slot>
        </div>
    </div>
</template>

<script>

/**
 *  ----------------------------
 * |          Header            |
 * |--------------------------- |
 * |         Content            |
 * | which fills remainin space |
 * |                            |
 * |                            |
 * |                            |
 *  ---------------------------
 * 
 * Root element must have fixed height and weigth to determine space of Card
 * 
 * Header doesn't grow 
 * Content grows to fill remaining space
 */

 export default {
    props: [

    ],

    components: {

    },

    data(){
        return {

        }
    },

    methods: {

    },
 }

</script>
