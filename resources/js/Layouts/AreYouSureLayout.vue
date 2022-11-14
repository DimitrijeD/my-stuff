<template>
    <div class="select-none relative overflow-hidden rounded">
        <div class="absolute top-0 left-0 h-full w-full flex flex-col">
            <div v-if="!show" @click="showMore()" class="grow cursor-pointer grid grid-rows-1 place-content-center">
                <slot name="button-as-wrapper"></slot><!--  'grow' on button is required to be provided from parent ;( -->
            </div>
            <div v-else class="grow flex flex-col h-full overflow-hidden ">
                <div class="flex flex-row place-items-center h-full">

                    <div class="w-full h-full scroll1 grid grid-rows-1 place-content-center">
                        <p class="break-words p-1 m-auto">
                            <slot name="question"></slot>
                        </p>
                    </div>

                    <div class="flex">
                        <slot name="yes"></slot>
                        <div @click="showMore()">
                            <slot name="no"></slot>
                        </div>
                    </div>

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
    
<style scoped>

</style>