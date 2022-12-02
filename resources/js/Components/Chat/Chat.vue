<template>
    <TransitionGroup ref="wrap" name="list" tag="div" class="w-full invisible absolute left-0 bottom-0 right-0 overflow-hidden flex items-stretch space-x-3" :class="[!isFullScreen ? 'pl-2' : '']">
        <ChatWindow v-for="group_id in openedGroupsIds" :key="group_id" :group_id="group_id" :size="size" />
    </TransitionGroup>
</template>

<script>
import { mapGetters } from 'vuex';
import ChatWindow from "@/Components/Chat/ChatWindow/ChatWindow.vue";
import { Resizer } from '@/UtilityFunctions/Resizer/Resizer.js' 

export default {
    inject: ['headerHeight'],

    components:{ ChatWindow, },

    computed: {
        ...mapGetters({ 
            user: "user",
            openedGroupsIds: ns.groupsManager('openedGroupsIds'),
        }),
    },

    data(){
        return {
            size: {},
            isFullScreen: false,
            
            resizer: new Resizer({
                maintainOffsetTop: window.innerHeight > 500 ? this.headerHeight : 0, // on smaller devices, chat fills entire window, on larger chat goes up to header
                heightGrowthBy: {
                    height: 8/100,
                    width: 6/100,
                },
                widthGrowthBy: {
                    height: 6/100,
                    width: 6/100,
                },
                minWidth: 400,
                minHeight: 580,

                onFullScreen: {
                    width: '100%',
                },
                dontApplyClasses: true, 
            })
        }
    },

    created(){
        this.$store.dispatch(ns.groupsManager('init'))
    },

    mounted(){
        this.resizer.setElement(this.$refs.wrap.$el)
        this.resize() 
        window.addEventListener("resize", this.resize)
    },

    methods: {
        resize() {
            let temp = this.resizer.run()

            if(!temp) return

            this.isFullScreen = temp.isFullScreen
            this.size.width  = temp.classes.width
            this.size.height = temp.classes.height
        },
    },
}
</script>

<style scoped>
.list-move,
.list-enter-active {
    transition: all 0.2s ease-in;
}
.list-leave-active {
    transition: all 0.2s ease-in;
}

.list-enter-from {
    opacity: 0;
    transform: translateY(500px);
}
.list-leave-to {
    opacity: 0;
    transform: scale(0);
}

.list-leave-active {
    position: absolute;
}
</style>