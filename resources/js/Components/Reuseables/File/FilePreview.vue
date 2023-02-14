<template>
    <img v-if="isImage(file.url)" class="h-full m-auto object-cover rounded opacity-80 hover:scale-105" :src="file.url" alt="no file">

    <FileSlot v-else>
        <TextFileShortcut :name="fileNameFromUrl(file.url)" class="w-full "/>
    </FileSlot>
</template>

<script>
import TextFileShortcut from '@/Components/Reuseables/File/TextFileShortcut.vue'
import FileSlot from '@/Components/Reuseables/File/FileSlot.vue'
import * as FileUtilFuncs from '@/UtilityFunctions/file.js'

export default{
    props: [ 'file' ],

    components: { TextFileShortcut, FileSlot, },

    data(){
        return {

        }
    },

    methods: {
        isImage(url){
            return FileUtilFuncs.isImage(url)
        },

        fileNameFromUrl(url){
            return FileUtilFuncs.fileNameFromUrl(url)
        }
    }
}
</script>