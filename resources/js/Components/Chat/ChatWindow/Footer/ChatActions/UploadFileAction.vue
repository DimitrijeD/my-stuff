<template>
    <div>
        <input ref="fileUpload" type="file" @change="fileSelected" hidden>
        <AttachmentIcon @click.prevent="$refs.fileUpload.click()" class="action-icon action-icon-with-fill" />
    </div>
</template>

<script>
import AttachmentIcon from "@/Components/Reuseables/Icons/AttachmentIcon.vue"

export default{
    inject: [ 'group_id' ],

    components: { AttachmentIcon, },

    data(){
        return {

        }
    },

    computed: {

    },

    methods: {
        fileSelected(e){
            this.$store.dispatch(ns.groupModule(this.group_id, 'newMessageM/addFile'), e.target.files[0])
        },
    },

}
</script>