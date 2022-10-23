<template>
    <ChatParticipants :group="group" />
    <WindowManagement :group="group" />
</template>

<script>
import ChatParticipants from '@/Components/Chat/ChatWindow/Header/ChatParticipants.vue'
import WindowManagement from "@/Components/Chat/ChatWindow/Header/WindowManagement.vue"

export default {
    props: [ 'group', ],

    components: { ChatParticipants, WindowManagement, },
}

</script>