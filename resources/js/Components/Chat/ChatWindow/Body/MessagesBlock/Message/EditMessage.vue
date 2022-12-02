<template>
    <div>
        <PenIcon 
            v-if="!openEdit" 
            @click="toggle()" 
            class="message-icon stroke-gray-500 fill-gray-500 hover:stroke-gray-600 dark:hover:stroke-gray-400" 
        />

        <div v-else class="flex p-2 gap-1.5 bg-gray-200 dark:bg-darker-200/60 rounded-xl">
            <textarea 
                class="bg-darker-400 grow rounded resize-none p-2 outline-none" 
                rows="3" 
                v-model="newText"
            ></textarea>

            <button 
                :class="['p-2 grid place-items-center bg-darker-400 rounded ', 
                    validateNewText 
                        ? 'text-green-500 ' 
                        : 'text-gray-400 cursor-not-allowed'
                    ]" 
                @click="update()"
            >Save</button>

            <button 
                class="p-2 grid place-items-center bg-darker-400 rounded text-gray-400" 
                @click="toggle()"
            >Close</button>
        </div>
    </div>
</template>

<script>
import PenIcon from '@/Components/Reuseables/Icons/PenIcon.vue'

export default {
    inject: ['group_id'],

    props: ['message_id', 'message'],

    components: { PenIcon, },

    data(){
        return {
            openEdit: false,
            newText: this.message.text
        }
    },

    computed: {
        validateNewText(){
            if(this.newText == this.message.text) return false
            if(!this.newText.trim()) return false

            return true
        }
    },

    created(){

    },

    methods: {
        toggle(){
            this.openEdit = !this.openEdit
            this.$emit('showEdit', this.openEdit)
        },

        update(){
            if(!this.validateNewText) return

            this.$store.dispatch(ns.groupModule(this.group_id, 'messagesM/updateMessage'), {
                text: this.newText,
                message_id: this.message_id,
            }).then(() => {
                this.toggle()
            })
        },
    }

}
</script>

<style scoped>

</style>