<template>
    <div v-show="showFiles" class="absolute w-full h-full top-0 bg-darker-100 overflow-hidden select-none">
        <div class="relative w-full h-full">
            <CloseIcon @click="close" class="absolute top-0 right-0 fill-gray-400 w-8 z-[1]" />
            
            <div class="absolute h-full w-full flex flex-col gap-2 py-2">
                <div class="mx-auto w-[90%] grow overflow-hidden flex">
                    <Image 
                        v-if="currentView" 
                        :url="currentView.url"
                        :key="'main-view'"
                        :imgCls="'m-auto w-auto h-auto max-w-full max-h-full'"
                    />
                </div>

                <div class="flex-none h-[30%]">
                    <div class="h-full flex justify-between space-x-2 bg-gradient-to-b from-darker-300 pt-2">
                        <ViewChanger  
                            :index="viewIndex"
                            :hasMore="hasPrevious"
                            :directionCls="'rotate-90'"
                            @changeViewIndex="previous"
                        />

                        <div :class="[`gap-2 grid w-full h-full grid-cols-3`]" :key="'full-view'">
                            <ImageFull 
                                v-for="image in previewImages" 
                                :url="image.url" 
                                :selected="image.id == currentView.id"
                                @click="selectCurrent(image.id)" 
                                class="cursor-pointer"
                                :key="image.id"
                            />
                        </div>

                        <ViewChanger  
                            :index="viewIndex"
                            :hasMore="hasNext"
                            :directionCls="'-rotate-90'"
                            @changeViewIndex="next"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CloseIcon from "@/Components/Reuseables/Icons/DeclineIcon.vue";
import * as FileUtilFuncs from '@/UtilityFunctions/file.js';
import ImageFull from '@/Components/Reuseables/File/Preview/ImageFull.vue';
import ArrowHeadIcon from '@/Components/Reuseables/Icons/ArrowHeadIcon.vue';
import Image from '@/Components/Reuseables/Image.vue';
import ViewChanger from '@/Components/Chat/ChatWindow/Body/FileWindow/ViewChanger.vue';

export default{
    inject: ['group_id'],

    components: { CloseIcon, ImageFull, ArrowHeadIcon, Image, ViewChanger, },

    data(){
        return {
            viewIndex: 0,
        }
    },
    
    computed: {
        showFiles(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'filesM/opened') ]
        },

        images(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'filesM/images') ]
        },

        previewImages(){
            return this.images.slice(this.viewIndex, this.viewIndex + this.numberOfPreviews)
        },

        currentView(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'filesM/currentView') ]
        },

        numberOfPreviews(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'filesM/numberOfPreviews') ]
        },

        hasPrevious(){
            return this.viewIndex > 0
        },

        hasNext(){
            return this.viewIndex + this.numberOfPreviews < this.images.length
        },

    },

    methods: {
        close(){
            this.$store.dispatch(ns.groupModule(this.group_id, 'filesM/toggleOpened'))
        },
 
        selectCurrent(id){
            this.$store.dispatch(ns.groupModule(this.group_id, 'filesM/currentViewId'), id)
        },

        next(){
            if(this.viewIndex + 1 + this.numberOfPreviews <= this.images.length)
                this.viewIndex++
        },

        previous(){
            if(this.viewIndex - 1 >= 0)
                this.viewIndex--
        },

    },
}
</script>
