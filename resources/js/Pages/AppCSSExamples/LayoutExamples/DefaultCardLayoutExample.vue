<template>
    <ExamplesLayout>
        <template #layout-name>
            <h1>Default Card Layout</h1>
        </template>

        <template #info-ul>
            <li></li>
        </template>

        <template #issues-ul>
            <li></li>
        </template>

        <template #body>
            <DefaultCardLayout class="h-96 w-96">
                <template #header>
                    <p  class="examples-header">Header of card</p>
                </template>
                        
                <template #body>
                    <div class="examples-body ">
                        <p class="pb-20">asd</p>
                        <p class="pb-20">asd</p>

                        <p class="pb-20">asd</p>

                        <p class="pb-20">asd</p>


                        <p class="pb-20">asd</p>

                        <p class="pb-20">asd</p>

                    </div>
                </template>

                <template #footer>
                    <p  class="examples-footer">footer</p>
                </template>
            </DefaultCardLayout>
        </template>
    </ExamplesLayout>
</template>

<script>

import DefaultCardLayout from '@/Layouts/DefaultCardLayout.vue'
import ExamplesLayout from '@/Layouts/ExamplesLayout.vue'

export default {
    components:{
        DefaultCardLayout,
        ExamplesLayout,
    },

    data(){
        return {

        }
    },


}
</script>