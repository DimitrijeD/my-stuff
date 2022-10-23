<template>
    <ExamplesLayout>
        <template #layout-name>
            <h1>DoubleScrollContentCardLayout examples</h1>
        </template>

        <template #info-ul>
            <li>This layout fills entire width of parent</li>
            <li>Header and footer are fixed, while body (scrolls) will grow to fill remaining space</li>
            <li>Scrolls are parallel with same width.</li>
            <li>If there is no content in one or both scrollable areas, it will just be blank </li>
        </template>

        <template #issues-ul>
            <li>Layout must have defined height, otherwise scrolls will not appear.</li>
        </template>

        <template #body>
            <DoubleScrollContentCardLayout class="w-96 h-96">
                <template #header >
                    <div class="examples-header">
                        This example has w-96 h-96
                    </div>
                </template>

                <template #content-left>
                    <div class="examples-body">
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                    </div>
                </template>

                <template #content-right>
                    <div class="examples-body">
                        <p>But if there isn't enough content, it wont scoll</p>
                    </div>
                </template>

                <template #footer>
                    <div class="examples-footer">
                        Footer
                    </div>
                </template>
            </DoubleScrollContentCardLayout>


            <DoubleScrollContentCardLayout class=" h-[600px]">
                <template #header >
                    <div class="examples-header">
                        This example has full width and h-[600px]
                    </div>
                </template>

                <template #content-left>
                    <div class="examples-body">
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>

                    </div>
                </template>

                <template #content-right>
                    <div class="examples-body">
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                    </div>
                </template>

                <template #footer>
                    <div class="examples-footer">
                        Footer
                    </div>
                </template>
            </DoubleScrollContentCardLayout>

            <div>Header and footer will grow while body will fill remaining space</div>

            <DoubleScrollContentCardLayout class=" h-[600px]">
                <template #header >
                    <div class="examples-header">
                        Bigger header
                    </div>
                </template>

                <template #content-left>
                    <div class="examples-body">
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>

                    </div>
                </template>

                <template #content-right>
                    <div class="examples-body">
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                        <p> Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br>  Lot of Content which oveflows <br>Lot of Content which oveflows <br> </p>
                    </div>
                </template>

                <template #footer>
                    <div class="examples-footer">
                        Bigger Footer
                    </div>
                </template>
            </DoubleScrollContentCardLayout>
        </template>
    </ExamplesLayout>

    
</template>

<script>
import DoubleScrollContentCardLayout from '@/Layouts/DoubleScrollContentCardLayout.vue'
import ExamplesLayout from '@/Layouts/ExamplesLayout.vue'

export default {
    components:{
        DoubleScrollContentCardLayout,
        ExamplesLayout,
    },
}

</script>


<style scoped>

</style>