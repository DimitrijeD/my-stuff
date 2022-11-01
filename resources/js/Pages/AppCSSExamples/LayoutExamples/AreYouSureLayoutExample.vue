<template>
    <ExamplesLayout>
        <template #layout-name>
            <h1>AreYouSureLayout examples</h1>
        </template>

        <template #info-ul>
            <li>Click on "Show more" will remove button <span class="examples-span-emphasis">replacing</span> it's content with <span class="examples-span-emphasis">confirmation area</span></li>
            <li>Intent is to have <span class="examples-span-emphasis">fixed area</span>  for main button and content which is being revealed</li>
            <li><span class="examples-span-emphasis">Question</span>, <span class="examples-span-emphasis">yes</span> and <span class="examples-span-emphasis">no</span> components should be aligned horisontaly</li>
            <li> AreYouSureLayout must have padding or height - <span class="text-bold text-blue-600"> {{'<AreYouSureLayout class="py-10 ...'}}</span></li>
        </template>

        <template #issues-ul>
            <li><span class="examples-span-emphasis">Confirmation area</span> is not centered.</li>
            <li>Button must have class="grow" - <span class="text-bold text-blue-600"> {{'<button class=" grow" ></button>'}}</span></li>
            <li>Question para must have <span class="text-blue-600"> "h-full"</span> in order to show full scroll when overflowing</li>
        </template>

        <template #body>
            <AreYouSureLayout class="py-10 border border-gray-400">
                <template #button-as-wrapper>
                    <button>Show more - with padding .................</button>
                </template>
                <template #question>
                    Question - Do you agree? Are you sure? etc.. Question - Do you agree? Are you sure? etc..
                </template>

                <template #yes>
                    <AcceptIcon class="test1" />
                </template>

                <template #no>
                    <DeclineIcon class="test1" />
                </template>
            </AreYouSureLayout>

            <AreYouSureLayout class="h-20 border border-gray-400">
                <template #button-as-wrapper>
                    <button  >Show more - with height</button>
                </template>
                <template #question>
                    <p>
                        Question - Do you agree? Are you sure? etc..
                    </p>
                </template>

                <template #yes>
                    <AcceptIcon class="test1" />
                </template>

                <template #no>
                    <DeclineIcon class="test1" />
                </template>
            </AreYouSureLayout>

            <AreYouSureLayout class="py-10 w-96 border border-gray-400">
                <template #button-as-wrapper>
                    <button class=" grow" >Show more - with limited width</button>
                </template>
                <template #question>
                    <p class="text-gray-400 ">
                        Question - Do you agree? Are you sure? etc..
                    </p>
                </template>

                <template #yes>
                    <AcceptIcon class="test1" />
                </template>

                <template #no>
                    <DeclineIcon class="test1" />
                </template>
            </AreYouSureLayout>

            <AreYouSureLayout class="py-10 w-96 border border-gray-400">
                <template #button-as-wrapper>
                    <button class=" grow" >Show more - with limited width and long question which scrolls</button>
                </template>
                <template #question>
                    <p class="text-gray-400 h-full">
                        START 123123  123123 123123 123123 123123 123123
                        Question - Do you agree? Are you sure? etc..
                        Question - Do you agree? Are you sure? etc..
                        Question - Do you agree? Are you sure? etc..
                        END
                        
                    </p>
                </template>

                <template #yes>
                    <AcceptIcon class="test1" />
                </template>

                <template #no>
                    <DeclineIcon class="test1" />
                </template>
            </AreYouSureLayout>
        </template>
    </ExamplesLayout>
</template>

<script>
import ExamplesLayout from '@/Layouts/ExamplesLayout.vue';

import AreYouSureLayout from '@/Layouts/AreYouSureLayout.vue';

import DeclineIcon from "@/Components/Reuseables/Icons/DeclineIcon.vue";
import AcceptIcon from "@/Components/Reuseables/Icons/AcceptIcon.vue";

export default {
    components:{
        AreYouSureLayout,
        DeclineIcon,
        AcceptIcon,
        ExamplesLayout,
    },
}
</script>

<style scoped>
.test1 {
    @apply h-14 fill-white ; 
}
</style>