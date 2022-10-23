<template>
    <ExamplesLayout>
        <template #layout-name>
            <h1>LabelInputButtonLayout examples</h1>
        </template>

        <template #info-ul>
            <li><span class="examples-span-emphasis">LabelInputButtonLayout</span> can receive <span class="examples-span-emphasis">inputWidthCls</span> prop.</li>
            <li>Property should be valid <span class="examples-span-emphasis">width</span> class such as <span class="examples-span-emphasis">'min-w-[50%]'</span> and/or <span class="examples-span-emphasis">'max-w-[60%]'</span></li>
            <li>For inputs which have longer description <span class="examples-span-emphasis">(label)</span>, use <span class="examples-span-emphasis">min-w-[m%] max-w-[n%]</span> in order to have more space for label. Examples below have some of those cases </li>
        </template>

        <template #issues-ul>
            <li>None</li>
        </template>

        <template #body>
            <LabelInputButtonLayout class="border border-darker-300 w-[29%]" :inputWidthCls="'min-w-[40%] max-w-[40%]'">
                <template #label>
                    <label class="">Typical label 40%</label>
                </template>

                <template #input>
                    <input type="text" class="small-input"  placeholder="placeholder">
                </template>

                <template #button>
                    <button :class="[
                        'p-2 rounded-xl border border-darker-500',
                    ]">
                    Action</button>
                </template>
            </LabelInputButtonLayout>


            <LabelInputButtonLayout class="border border-darker-300 w-[29%]" :inputWidthCls="'min-w-[40%] max-w-[40%]'">
                <template #label>
                    <label class="">Label with 40% but more text text text text text text text text </label>
                </template>

                <template #input>
                    <input type="text" class="small-input"  placeholder="placeholder">
                </template>

                <template #button>
                    <button :class="[
                        'p-2 rounded-xl border border-darker-500',
                    ]">
                    Action</button>
                </template>
            </LabelInputButtonLayout>

            <LabelInputButtonLayout class="border border-darker-300 w-[29%]">
                <template #label>
                    <label class="">Without 'minWidthCls' prop</label>
                </template>

                <template #input>
                    <input type="text" class="small-input"  placeholder="placeholder">
                </template>

                <template #button>
                    <button :class="[
                        'p-2 rounded-xl border border-darker-500',
                    ]">
                    Action</button>
                </template>
            </LabelInputButtonLayout>

            <LabelInputButtonLayout class="border border-darker-300 w-[46%]" >
                <template #label>
                    <label class="">Label with w-[46%]<br>Label expands when there is lot of text while entire layout is still centered</label>
                </template>

                <template #input>
                    <input type="text" class="small-input"  placeholder="placeholder">
                </template>

                <template #button>
                    <button :class="[
                        'p-2 rounded-xl border border-darker-500',
                    ]">
                    Action</button>
                </template>
            </LabelInputButtonLayout>

            <LabelInputButtonLayout class="border border-darker-300 w-full" >
                <template #label>
                    <label class="">Full sceen mode, input grows filling entire width of screen</label>
                </template>

                <template #input>
                    <input type="text" class="small-input"  placeholder="placeholder">
                </template>

                <template #button>
                    <button :class="[
                        'p-2 rounded-xl border border-darker-500',
                    ]">
                    Action</button>
                </template>
            </LabelInputButtonLayout>

            <LabelInputButtonLayout class="border border-darker-300 w-full" :inputWidthCls="'min-w-[60%] max-w-[60%]'">
                <template #label>
                    <label class="">Full sceen mode, defined max width for input</label>
                </template>

                <template #input>
                    <input type="text" class="small-input"  placeholder="placeholder">
                </template>

                <template #button>
                    <button :class="[
                        'p-2 rounded-xl border border-darker-500',
                    ]">
                    Action</button>
                </template>
            </LabelInputButtonLayout>

            <LabelInputButtonLayout class="border border-darker-300 w-full" :inputWidthCls="'min-w-[60%] max-w-[60%]'">
                <template #label>
                    <label class="">Full sceen mode, defined max width for input <span class="examples-span-emphasis">(same min max width as previous example)</span>() but much longer label. Input should be same width as previous example, while this lable grows on y axis and everyutjoing is still centered</label>
                </template>

                <template #input>
                    <input type="text" class="small-input"  placeholder="placeholder">
                </template>

                <template #button>
                    <button :class="[
                        'p-2 rounded-xl border border-darker-500',
                    ]">
                    Action</button>
                </template>
            </LabelInputButtonLayout>
        </template>
    </ExamplesLayout>
</template>

<script>
import LabelInputButtonLayout from '@/Layouts/LabelInputButtonLayout.vue'
import ExamplesLayout from '@/Layouts/ExamplesLayout.vue'

export default {
    components:{
        LabelInputButtonLayout, ExamplesLayout,
    },

    data(){
        return {
            minWidthCls: 'min-w-[60%] max-w-[60%]',
        }
    },

}
</script>

<style scoped>

</style>