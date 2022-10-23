<template>
    <ExamplesLayout>
        <template #layout-name>
            <h1>FillRemainingSpaceLayout examples</h1>
        </template>

        <template #info-ul>
            <li>Layout must have defined height.</li>
            <li>Header is fixed, while content will grow to fill remaining space</li>
            <li>Content will not scoll even if content oveflows (content will be hidden)</li>
        </template>

        <template #issues-ul>
            <li>None</li>
        </template>

        <template #body>
            <FillRemainingSpaceLayout class="w-[400px] h-[50px]">
                <template #header>
                    <p class="examples-header ">Header h-[50px], h is too small, wont display content</p>
                </template>

                <template #content>
                    <div class="examples-body">
                        <p>Something</p>
                        <p>Content</p>
                        <p>Place what ever here</p>
                    </div>
                </template>
            </FillRemainingSpaceLayout>

            <FillRemainingSpaceLayout >
                <template #header>
                    <p class="examples-header">No width or heigth defined</p>
                </template>

                <template #content>
                    <div class="examples-body">
                        <p>Content </p>
                        <p>will</p>
                        <p>grow</p>
                        <p>as</p>
                        <p>much</p>
                        <p>as</p>
                        <p>it</p>
                        <p>needs</p>
                        <p>pushing</p>
                        <p>everything</p>
                        <p>down</p>
                    </div>
                </template>
            </FillRemainingSpaceLayout>

            <div class="h-[400px]">
                <FillRemainingSpaceLayout >
                    <template #header>
                        <p class="examples-header">Parent height is defined as 400px</p>
                    </template>

                    <template #content>
                        <div class="examples-body space-y-9">
                            <p>Content will fill remaining height  </p>
                            <p>In this case it is (400px - Header = remaining space).</p>
                            <p>Remaining content will be overflow hidden. Number '3' is last para and is not displayed.</p>
                            <p>As Content slot, add scrollable component to prevent overflow hidden</p>
                            <p>1</p>
                            <p>2</p>
                            <p>3</p>
                        </div>
                    </template>
                </FillRemainingSpaceLayout>
            </div>

            <div class="h-[300px] w-[300px]">
                <FillRemainingSpaceLayout>
                    <template #header>
                        <p class="examples-header">h-[300px] w-[300px]</p>
                    </template>

                    <template #content>
                        <div class="examples-body">
                            <p>Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something Something </p>
                        </div>
                    </template>
                </FillRemainingSpaceLayout>
            </div>
        </template>
    </ExamplesLayout>
</template>

<script>
import FillRemainingSpaceLayout from '@/Layouts/FillRemainingSpaceLayout.vue'
import ExamplesLayout from '@/Layouts/ExamplesLayout.vue'

export default {
    components:{
        FillRemainingSpaceLayout, ExamplesLayout,
    },
}

</script>