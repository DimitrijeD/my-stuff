<template>
    <div class="relative" ref="wrap" v-click-away="clickAway">
        <div class="flex h-full relative">
            <MailIcon @click="toggle" :class="[ 'h-full w-[3rem] p-1 opacity-90 stroke-transparent', iconCls ]" />
            <!-- <span v-if="numGroupsWithUnseen" 
                :class="['absolute top-0 -right-1', 
                numGroupsWithUnseen ? 'text-green-600 font-bold' : 'text-blue-500/90 dark:text-gray-300/80']"
            >{{ numGroupsWithUnseen }}</span> -->
        </div>

        <div ref="dropdown" v-show="show" class="def-dropdown">
            <div class="flex">
                <CardNavBtn v-for="(comp, name ) in navComponents" :isActive="comp.show" @click="chatNav(name)">
                    {{ comp.text }} 
                </CardNavBtn>
            </div>
            <div class="grow flex flex-col pt-2">
                <KeepAlive>
                    <component :is="showComp" class="grow" />
                </KeepAlive>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex"
import CreateChatGroup from "@/Components/Chat/ChatDropdown/CreateChatGroup.vue";
import ChatHistory from '@/Components/Chat/ChatDropdown/ChatHistory.vue'
import MailIcon from "@/Components/Reuseables/Icons/MailIcon.vue"
import CardNavBtn from '@/Components/Reuseables/Buttons/CardNavBtn.vue'
import { Resizer } from '@/UtilityFunctions/Resizer/Resizer.js' 

export default {
    inject: ['headerHeight'],

    components: { CreateChatGroup, ChatHistory, MailIcon, CardNavBtn },

    data(){
        return {
            navComponents: {
                ChatHistory: {
                    text: 'Chat history',
                    show: true,
                },
                CreateChatGroup: {
                    text: 'Create chat',
                    show: false,
                },
            },

            resizer: new Resizer({
                maintainOffsetTop: this.headerHeight,
                heightGrowthBy: {
                    height: 8/100,
                    width: 6/100,
                },
                widthGrowthBy: {
                    height: 6/100,
                    width: 6/100,
                },
                minWidth: 400,
                minHeight: 530,
                onResize: {
                    left: 'auto',
                },
                onFullScreen: {
                    width: '100%',
                    left: 0,
                }
            }),
        }
    },

    computed: {
        ...mapGetters({ 
            numGroupsWithUnseen: ns.groupsManager('numGroupsWithUnseen'),
            show: ns.chatDropdown('show'),
        }),

        iconCls(){
            if(this.numGroupsWithUnseen) return 'fill-green-600/90'

            if(!this.numGroupsWithUnseen) return 'fill-blue-500/90 dark:fill-gray-300/80'

            return 'fill-gray-400' 
        },

        showComp(){
            for(let i in this.navComponents){
                if(this.navComponents[i].show) return i
            }

            return ''
        }
    },

    mounted(){
        this.resizer.setElement(this.$refs.dropdown)
        this.resize()
    },

    watch: {
        show(){
            if(this.show){
                this.resize()
                window.addEventListener("resize", this.resize)
            } else {
                window.removeEventListener("resize", this.resize)
            }
        }
    },

    methods: {
        clickAway(){
            if(this.show) this.$store.dispatch(ns.chatDropdown('toggle'))
        },

        resize(){
            this.resizer.setTryOffsetLeft(this.$refs.wrap.getBoundingClientRect().left)
            this.resizer.run()
        },

        chatNav(componentName){
            for(let i in this.navComponents){
                this.navComponents[i].show = componentName == i
            }
        },

        toggle(){
            this.$store.dispatch(ns.chatDropdown('toggle'))
        },

    },
}
</script>