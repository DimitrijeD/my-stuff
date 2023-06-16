<template>
    <div class="flex flex-col h-full">
        <TextFileIcon class="block grow stroke-black shrink fill-gray-500" />
        <p class="mt-auto text-gray-400 text-sm flex-none truncate">
            <span>{{ this.name }}</span>
        </p>
    </div>
</template>

<script>
import TextFileIcon from '@/Components/Reuseables/Icons/TextFileIcon.vue'

export default{
    props: [ 'name' ],

    components: { TextFileIcon, },

    data(){
        return {

        }
    },

    methods: {
        
    }
}
</script>