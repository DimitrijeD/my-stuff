<template>
    <div class="shadow p-1 mr-1.5 cursor-pointer border overflow-hidden h-[200px] flex flex-col rounded-xl font-light
        bg-blue-200 dark:bg-darker-200"
        :class="[ seen 
            ? 'border-blue-400 dark:border-darker-400 dark:hover:bg-darker-200 shadow-gray-400 dark:shadow-blue-300'
            : 'border-green-400 dark:border-green-600/40 shadow-green-400 dark:shadow-green-600'
        ]"
    >
        <div class="mx-2 text-sm pt-1" v-if="groupName">
            <div class="flex">
                <p :class="['p-0.5 truncate', !seen ? 'text-green-800 font-normal' : 'dark:text-blue-300']">
                    <slot name="group-name"></slot>
                </p>
            </div>
            <!-- <hr class="delimiter-x" /> -->
        </div>

        <div class="text-gray-800 dark:text-gray-400 grow flex flex-row overflow-hidden">
            <div v-if="atLeastTwoParticpants" class=" p-1 w-2/5 scroll1">
                <slot name="participants"></slot>
            </div>  
            <div v-else class=" p-1 w-2/5 italic truncate">
                {{ txtOneParticipant }}
            </div>

            <hr class="delimiter-y mr-2" />

            <div class="w-3/5 pt-1 text-gray-700 dark:text-gray-300">
                <slot name="last-message"></slot>
            </div>  
        </div>  
    </div>
</template>

<script>

export default{
    props: ['seen', 'atLeastTwoParticpants', 'groupName'],

    components:{
        
    },

    data(){
        return {
            txtOneParticipant: "Only you.",
        }
    }

}
</script>

<style scoped>
.delimiter-x { @apply h-0.5 w-full bg-gray-300 dark:bg-darker-400 border-0; }
.delimiter-y { @apply h-full w-0.5 bg-gray-200 dark:bg-darker-300 border-0; }

    

</style>