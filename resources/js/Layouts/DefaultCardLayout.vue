<template>
    <div class="flex w-full h-full flex-col overflow-hidden "> 
        <!-- Header -->
        <div class="">
            <slot name="header"></slot>
        </div>
        <!-- / -->

        <!-- Content -->
        <div class="grow overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 bottom-0 scroll1 space-y-2">
                <slot name="content"></slot>
            </div>
        </div>
        <!-- / -->

        <!-- Footer -->
        <div class="">
            <slot name="footer"></slot>
        </div>
        <!-- / -->
    </div>

</template>

<script>
// <DefaultCardLayout>
//     <template #header>

//     </template>
            
//     <template #content>

//     </template>

//     <template #footer>

//     </template>
// </DefaultCardLayout>
/**
 * |---------------------------|
 * |          Header           |
 * |---------------------------|
 * |         Content           |
 * |                           |
 * | Is scrollable area        |
 * | no matter how much        |
 * | content there is (if any) |
 * |---------------------------|
 * |          Footer           |
 * |---------------------------|
 * 
 * Root element must have fixed height and weigth to determine space of Card
 * 
 * Header doesn't grow 
 * Content grows to fill remaining space
 * Footer doesn't grow
 * 
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
