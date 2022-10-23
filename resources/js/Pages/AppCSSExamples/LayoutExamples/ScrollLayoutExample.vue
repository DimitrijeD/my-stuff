<template>
    <ExamplesLayout>
        <template #layout-name>
            <h1>Scroll Layout examples</h1>
        </template>

        <template #info-ul>
        </template>

        <template #issues-ul>
        </template>

        <template #body>
            <div class="space-y-10">
                <ScrollLayout class="w-[500px] h-[200px] mx-auto ">
                    <template #scroll-content>
                        <div class="py-20 bg-red-500">ajsndkas ajsdk</div>
                        <div class="py-20 bg-red-600">ajsndkas ajsdk</div>
                        <div class="py-20 bg-red-700">ajsndkas ajsdk</div>
                        <div class="py-20 bg-red-800">ajsndkas ajsdk</div>
                    </template>
                </ScrollLayout>

                <ScrollLayout class="w-[500px] h-[200px] mx-auto ">
                    <template #scroll-content>
                        <div class="py-20 bg-blue-500">ajsndkas ajsdk</div>
                        <div class="py-20 bg-blue-600">ajsndkas ajsdk</div>
                        <div class="py-20 bg-blue-700">ajsndkas ajsdk</div>
                        <div class="py-20 bg-blue-800">ajsndkas ajsdk</div>
                    </template>
                </ScrollLayout>

                <ScrollLayout class="w-[500px] h-[200px] mx-auto bg-yellow-500">
                    <template #scroll-content>
                        <div class="py-0 bg-gray-500">ajsndkas ajsdk</div>

                    </template>
                </ScrollLayout>
            </div>
        </template>
    </ExamplesLayout>

</template>

<script>
import ExamplesLayout from '@/Layouts/ExamplesLayout.vue'
import ScrollLayout from '@/Layouts/ScrollLayout.vue'


export default {
    components:{
        ScrollLayout, ExamplesLayout,
    },

    data(){
        return {
            
        }
    },

}
</script>

<style scoped>

</style>