<template>
    <div class="default-group-card "
        :class="[ seen 
            ? 'dark:hover:bg-darker-200 '
            : 'green-glow'
        ]"
    > 
        <div class="mx-2 pt-1" v-if="groupName">
            <div class="flex">
                <p class="bg-gray-300/50 dark:bg-darker-300/70 rounded-lg p-1 truncate">
                    <slot name="group-name"></slot>
                </p>
            </div>
        </div>

        <div class="text-gray-800 dark:text-gray-400 grow flex flex-row overflow-hidden gap-1">
            <div v-if="atLeastTwoParticpants" class=" p-1 w-2/5 scroll1">
                <slot name="participants"></slot>
            </div>  
            <div v-else class=" p-1 w-2/5 italic truncate">
                {{ txtOneParticipant }}
            </div>

            <div class="w-3/5 pt-1 text-gray-700 dark:text-gray-300">
                <slot name="last-message"></slot>
            </div>  
        </div>  
    </div>
</template>

<script>

export default{
    props: ['seen', 'atLeastTwoParticpants', 'groupName'],

    components:{  },

    data(){
        return {
            txtOneParticipant: "Only you.",
        }
    }

}
</script>