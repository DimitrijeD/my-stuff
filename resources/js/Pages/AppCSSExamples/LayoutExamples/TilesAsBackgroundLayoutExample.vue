<template>
    <ExamplesLayout>
        <template #layout-name>
            <h1>Tiles Layout examples</h1>
        </template>

        <template #info-ul>
        </template>

        <template #issues-ul>
        </template>

        <template #body>
            

            <TilesAsBackgroundLayout>

            </TilesAsBackgroundLayout>
        </template>
    </ExamplesLayout>

</template>

<script>
import ExamplesLayout from '@/Layouts/ExamplesLayout.vue'
import TilesAsBackgroundLayout from '@/Layouts/TilesAsBackgroundLayout.vue'

export default {
    components:{ ExamplesLayout, TilesAsBackgroundLayout },

    data(){
        return {
            
        }
    },

}
</script>

<style scoped>

</style>