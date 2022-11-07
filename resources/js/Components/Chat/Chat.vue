<template>
    <div class="absolute invisible left-0 bottom-0 w-full h-full overflow-hidden flex items-stretch space-x-3" :class="[!isFullScreen ? 'pl-2' : '']">
        <ChatWindow
            v-for="group_id in openedGroupsIds"
            :key="group_id"
            :group_id="group_id"
            :size="{ width: width, height: height }"
        />

    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import ChatWindow from "@/Components/Chat/ChatWindow/ChatWindow.vue";
import * as ns from '@/Store/module_namespaces.js'

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
            height: null,
            width: null,
            debounceResize: null,
            minWidth: 370,
            minHeight: 500,
            widthGrowthBy: {
                height: 6/100,
                width: 6/100,
            },
            heightGrowthBy: {
                height: 8/100,
                width: 6/100,
            },
            isFullScreen: false,
        }
    },

    created(){
        this.$store.dispatch(ns.groupsManager('init')).then(()=>{
            this.$store.dispatch(ns.groupsManager('sortNewstGroups'))
            this.$store.dispatch(ns.groupsManager('numGroupsWithUnseen'))
        })

        this.listenUserToUserNotifications()
    },

    mounted(){
        this.calcSize() 
        window.addEventListener("resize", this.calcSize)
    },

    methods: {
        listenUserToUserNotifications(){
            Echo.private(`App.Models.User.${this.user.id}`).listen('.message.notification', e => {
                this.$store.dispatch(ns.groupsManager('openGroup'), {group_id: e.data.group_id, initiatedBy: 'system'})
            })
        },

        calcSize(){
            if(this.debounceResize) return

            this.debounceResize = setTimeout(() => {
                const minWidth = 400
                const minHeight = 530

                if( this.isWidthOverflowing(minWidth) || this.isHeightOverflowing(minHeight) || this.isHeightOverflowing(minHeight + this.headerHeight)){
                    this.makeFullScreen()
                    this.debounceResize = null
                    return 
                }

                const widthGrowth  = window.innerWidth * this.widthGrowthBy.width   +  window.innerHeight * this.widthGrowthBy.height
                const heightGrowth = window.innerWidth * this.heightGrowthBy.width  +  window.innerHeight * this.heightGrowthBy.height

                const wVal = Math.round( minWidth  + widthGrowth )
                const hVal = Math.round( minHeight + heightGrowth )

                if( this.isWidthOverflowing(wVal) || this.isHeightOverflowing(hVal) || this.isHeightOverflowing(hVal + this.headerHeight)){
                    this.makeFullScreen()
                    this.debounceResize = null
                    return 
                }

                this.height = `${hVal}px` 
                this.width  = `${wVal}px`
                this.debounceResize = null
                this.isFullScreen = false
            }, 100)
        },
        
        isWidthOverflowing(width){
            return window.innerWidth < width
        },

        isHeightOverflowing(height){
            return window.innerHeight < height
        },

        makeFullScreen(){
            this.height = '100%' 
            this.width  = '100%'
            this.isFullScreen = true
        },
    },
}
</script>

<style>

</style>