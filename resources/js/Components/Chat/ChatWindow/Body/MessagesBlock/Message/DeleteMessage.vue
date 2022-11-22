<template>
    <div>
        <DeleteIcon v-if="!pendingConfirm" class="message-icon stroke-gray-500 fill-transparent hover:stroke-gray-600 dark:hover:stroke-gray-400" @click="show(true)" />
        
        <Transition name="slide" class="z-10 absolute w-full h-full flex items-center gap-1.5 px-4 bg-gray-200 dark:bg-darker-200/70 rounded-xl">
            <div v-if="pendingConfirm" >
                <p class="grow text-center">Delete this message? </p>
                <AcceptIcon class="w-10 h-10 fill-gray-600 hover:fill-red-500 stroke-transparent" @click="deleteMessage()" />
                <DeclineIcon class="w-10 h-10 stroke-transparent fill-gray-600" @click="show(false)" />
            </div>
        </Transition>
    </div>
</template>

<script>
import DeleteIcon from '@/Components/Reuseables/Icons/DeleteIcon.vue'
import DeclineIcon from '@/Components/Reuseables/Icons/DeclineIcon.vue'
import AcceptIcon from "@/Components/Reuseables/Icons/AcceptIcon.vue"

export default {
    inject: ['group_id'],

    props: ['message_id'],

    components: { DeleteIcon, DeclineIcon, AcceptIcon, },

    data(){
        return {
            pendingConfirm: false,
        }
    },

    methods: {
        deleteMessage(){
            this.$store.dispatch(ns.groupModule(this.group_id, 'deleteMessage'), {
                message_id: this.message_id
            })

            this.pendingConfirm = false
            this.$emit('pendingDelete', this.pendingConfirm)
        },

        show(bool){
            this.pendingConfirm = bool
            this.$emit('pendingDelete', this.pendingConfirm)
        }

    }
}
</script>

<style scoped>

.slide-enter-active{
    transition: all 0.2s ease-out;
}

.slide-leave-active {
    transition: opacity 0.1s linear;
    left: 0px;
    top: 0px;
}

.slide-enter-from {
    opacity: 0;
    transform: translateX(30px);
}

.slide-leave-to {
    opacity: 0;
}
</style>