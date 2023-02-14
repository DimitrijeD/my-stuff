<template>
    <Transition name="slide-fade">
        <TransitionGroup v-show="files.length" tag="div" name="fade" class="relative flex flex-wrap gap-2 py-1 bg-gradient-to-b from-darker-200">
            <div v-for="(file, index) in files" class="h-20 w-20 relative overflow-hidden" :key="index">
                <DeleteIcon class="absolute top-0 right-0 w-7 h-7 fill-gray-300 opacity-70 hover:opacity-100 mix-blend-difference " @click="removeFile(index)" />

                <ImagePreview v-if="isImage(file)" :url="getSrc(file)" />

                <FileSlot v-else>
                    <TextFilePreview :name="file.name" />
                </FileSlot>
            </div>
        </TransitionGroup>
    </Transition>
</template>

<script>
import * as FileUtilFuncs from '@/UtilityFunctions/file.js'
import FileSlot from '@/Components/Reuseables/File/FileSlot.vue'
import DeleteIcon from '@/Components/Reuseables/Icons/DeclineIcon.vue'
import ImagePreview    from '@/Components/Reuseables/File/Preview/ImagePreview.vue'
import TextFilePreview from '@/Components/Reuseables/File/Preview/TextFilePreview.vue'

export default{
    inject:['group_id'],

    components: { FileSlot, DeleteIcon, ImagePreview, TextFilePreview, },

    data(){
        return {
            
        }
    },

    computed: {
        files(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'newMessageM/files') ]
        },


    },

    methods: {
        getSrc(file){
            return FileUtilFuncs.getSrc(file)
        },

        isImage(file){
            return FileUtilFuncs.isImageByMime(file.type)
        },

        removeFile(index){
            this.$store.dispatch( ns.groupModule(this.group_id, 'newMessageM/removeFile'), index ) 
        }
    },
}
</script>

<style scoped>
.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(20px);
  opacity: 0;
}

.fade-move,
.fade-enter-active,
.fade-leave-active {
  transition: all 0.5s cubic-bezier(0.55, 0, 0.1, 1);
}

/* 2. declare enter from and leave to state */
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: scaleY(0.01) translate(30px, 0);
}

/* 3. ensure leaving items are taken out of layout flow so that moving
      animations can be calculated correctly. */
.fade-leave-active {
  position: absolute;
}
</style>