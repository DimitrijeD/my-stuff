<template>
    <div class="relative overflow-hidden">
        <div class="absolute top-0 left-0 h-full w-full flex">
            <div v-if="!show" @click="showMore()" class="w-full flex">
                <slot name="button-as-wrapper"></slot><!--  'grow' on button is required to be provided from parent ;( -->
            </div>
            <div v-else class="flex gap-2 items-center p-2">
                <div class="h-full overflow-y-auto flex items-center">
                    <slot name="question"></slot>
                </div>
                <div>
                    <slot name="yes"></slot>
                </div>
                <div @click="showMore()">
                    <slot name="no"></slot>
                </div>
            </div>
        </div>
    </div>
</template>
    
<script>
// <AreYouSureLayout>
//     <template #button-as-wrapper>

//     </template>
//     <template #question>

//     </template>
//     <template #yes>

//     </template>
//     <template #no>

//     </template>
// </AreYouSureLayout>
export default {
    props: [ ],

    components: {

    },

    data(){
        return {
            show: false
        }
    },

    methods: {
        showMore(){
            this.show = !this.show
        }
    },
}

</script>
    