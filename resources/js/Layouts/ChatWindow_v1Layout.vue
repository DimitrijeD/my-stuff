<template>
    <div :class="[ 'def-resize-trans self-end visible flex-none bg-white dark:bg-darker-300 text-gray-600 dark:text-gray-300']" 
        :style="{ height: group.window.minimized ? '' : size.height, width: size.width,  }"
    >
        <div class="flex flex-col overflow-hidden h-full">
            <div class="bg-blue-400 dark:bg-gradient-to-b dark:from-darker-500 dark:via-darker-500 dark:bg-transparent flex flex-nowrap gap-2 h-16"> 
                <slot name="header"></slot>
            </div>

            <div class="h-full flex flex-col border-l border-r border-blue-400 dark:border-none">
                <!-- messages -->
                <div v-show="!group.window.showConfig && !group.window.minimized" class="overflow-hidden grow pt-1 flex flex-col ">
                    <div class="h-full relative">
                        <ChatMessagesScroll :group="group">
                            <template #messages>
                                <slot name="messages"></slot>
                            </template>
                        </ChatMessagesScroll>
                        <slot name="action-response"></slot>
                    </div>

                    <slot name="footer"></slot>
                </div>
                <!-- / -->

                <!-- Config -->
                <div v-show="group.window.showConfig && !group.window.minimized" class="grow">
                    <slot name="config"></slot>
                </div>
                <!-- / -->
            </div>
        </div>
    </div>
</template>

<script>
import ChatMessagesScroll from '@/Layouts/Chat/ChatMessagesScroll.vue'

export default {
    props: [ 'group', 'size', ],

    components: { ChatMessagesScroll },
}

</script>