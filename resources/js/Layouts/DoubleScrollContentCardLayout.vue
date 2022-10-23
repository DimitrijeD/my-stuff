<template>
    <div class="flex flex-col space-y-2 overflow-hidden w-full h-full"> 
        <!-- Header -->
        <div class="flex-none">
            <slot name="header"></slot>
        </div>
        <!-- / -->

        <!-- Content -->
        <div class="grow">
            <div class="h-full flex flex-row gap-2 ">
                <div class="grow overflow-hidden relative">
                    <div class="absolute top-0 left-0 right-0 bottom-0 scroll1">
                        <slot name="content-left"></slot>
                    </div>
                </div>

                <hr class="w-0.5 h-full bg-gray-300 dark:bg-darker-400 border-0" />

                <div class="grow overflow-hidden relative">
                    <div class="absolute top-0 left-0 right-0 bottom-0 scroll1">
                        <slot name="content-right"></slot>
                    </div>
                </div>
            </div>
        </div>
        <!-- / -->

        <!-- Footer -->
        <div class="flex-none">
            <slot name="footer"></slot>
        </div>
        <!-- / -->
    </div>
</template>

<script>

/**
 * |------------------------------|
 * |          Header              |
 * |--------------|---------------|
 * |              |               |
 * |              |               |
 * |  Scrollable  |   Scrollable  |
 * |              |               |
 * |              |               |
 * |------------------------------|
 * |          Footer              |
 * |------------------------------|
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
