<template>
    <div class="flex justify-between py-0.5">
        <div class="flex justify-start">
            <CallIcon class="action-icon action-icon-with-stroke" />
        </div>
        <div class="flex justify-end">
            <UploadFileAction />
        </div>
    </div>
</template>

<script>
import CallIcon from "@/Components/Reuseables/Icons/CallIcon.vue"
import UploadFileAction from '@/Components/Chat/ChatWindow/Footer/ChatActions/UploadFileAction.vue'

export default{
    inject: [ 'group_id' ],

    components: { UploadFileAction, CallIcon, },

    data(){
        return {

        }
    },

    computed: {

    },

    methods: {
        
    },

}
</script>
