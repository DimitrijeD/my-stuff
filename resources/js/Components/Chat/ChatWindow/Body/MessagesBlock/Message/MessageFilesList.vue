<template>
    <div v-if="files.length" class="pt-1 pb-2 grid grid-cols-4 gap-2 place-items-center w-full">
        <div v-for="file in files" class="h-24 overflow-hidden w-full">
            <Image 
                v-if="isImage(file.url)" 
                @click.self="focusImage(file.id)"
                :url="file.url"
                :imgCls="'h-full m-auto object-cover rounded opacity-80 hover:scale-105'"
                :key="file.id"
            />

            <FileSlot v-else>
                <TextFileShortcut :name="fileNameFromUrl(file.url)" class="w-full "/>
            </FileSlot>
        </div>
    </div>
</template>

<script>
import TextFileShortcut from '@/Components/Reuseables/File/TextFileShortcut.vue';
import FileSlot from '@/Components/Reuseables/File/FileSlot.vue';
import * as FileUtilFuncs from '@/UtilityFunctions/file.js';
import Image from '@/Components/Reuseables/Image.vue';

export default {
    inject: ['group_id'],

    props: ['message_id' ],

    components: { TextFileShortcut, FileSlot, Image, },

    data(){
        return {

        }
    },

    computed: {
        files(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'messagesM/messageFiles') ](this.message_id);
        },
    },

    methods: {
        isImage(url){
            return FileUtilFuncs.isImage(url)
        },

        fileNameFromUrl(url){
            return FileUtilFuncs.fileNameFromUrl(url)
        },

        focusImage(id){
            this.$store.dispatch(ns.groupModule(this.group_id, 'filesM/focusImage'), {
                id: id, 
                message_id: this.message_id,
                openIfClosed: true,
            })
        }
    },

}
</script>
