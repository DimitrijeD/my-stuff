<template>
    <ChatParticipants />
    <WindowManagement />
</template>

<script>
import ChatParticipants from '@/Components/Chat/ChatWindow/Header/ChatParticipants.vue'
import WindowManagement from "@/Components/Chat/ChatWindow/Header/WindowManagement.vue"

export default {
    components: { ChatParticipants, WindowManagement, },
}

</script>