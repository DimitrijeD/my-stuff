<template>
    <div class="relative" ref="wrap" >
        <MailIcon @click="toggleClickListener" :class="[ 'h-full p-1 opacity-90 stroke-transparent', iconCls ]" />

        <div ref="dropdown" v-show="showDrop" class="def-dropdown">
            <div class="flex">
                <CardNavBtn :isActive="showNav.ChatHistory" @click="chatNav('ChatHistory')">
                    Chat history
                </CardNavBtn>
                <CardNavBtn :isActive="showNav.CreateChatGroup" @click="chatNav('CreateChatGroup')">
                    Create new chat
                </CardNavBtn>
            </div>
            <div class="grow flex flex-col pt-2 ">
                <ChatHistory     class="grow" v-show="showNav.ChatHistory"     @closeDropdown="removeClickListener" /> 
                <CreateChatGroup class="grow" v-show="showNav.CreateChatGroup" @closeDropdown="removeClickListener" /> 
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex"
import * as ns from '@/Store/module_namespaces.js'
import CreateChatGroup from "@/Components/Chat/ChatDropdown/CreateChatGroup.vue";
import ChatHistory from '@/Components/Chat/ChatDropdown/ChatHistory.vue'
import MailIcon from "@/Components/Reuseables/Icons/MailIcon.vue"
import CardNavBtn from '@/Components/Reuseables/Buttons/CardNavBtn.vue'

export default {
    components: { CreateChatGroup, ChatHistory, MailIcon, CardNavBtn },

    data(){
        return {
            height: null,
            width: null,
            positionLeft: null,
            debounceResize: null,
            minWidth: 400,
            minHeight: 530,
            widthGrowthBy: {
                height: 6/100,
                width: 6/100,
            },
            heightGrowthBy: {
                height: 8/100,
                width: 6/100,
            },
            headerHeight: 3*16,

            showDrop: false,

            showNav: {
                CreateChatGroup: false,
                ChatHistory: true
            },
        }
    },

    computed: {
        ...mapGetters({ 
            numGroupsWithUnseen: ns.groupsManager('numGroupsWithUnseen'),
        }),

        iconCls(){
            if(this.numGroupsWithUnseen) return 'fill-green-600/90'

            if(!this.numGroupsWithUnseen) return 'fill-blue-500/90 dark:fill-gray-300/80'

            return 'fill-gray-400' 
        },

        showComp(){
            for(let i in this.showNav){
                if(this.showNav[i]) return i
            }

            return ''
        }
    },

    mounted(){
        this.calcSize()
    },

    watch: {
        showDrop(){
            if(this.showDrop){
                this.calcSize()
                window.addEventListener("resize", this.calcSize)
            } else {
                window.removeEventListener("resize", this.calcSize)
            }
            
            this.$emit('dropdownToggled', {
                name: 'chat',
                opened: this.showDrop
            })
        }
    },

    methods: {
        calcSize(){
            if(this.debounceResize) return

            this.debounceResize = setTimeout(() => {
                if( this.isWidthOverflowing(this.minWidth) || this.isHeightOverflowing(this.minHeight) || this.isHeightOverflowing(this.minHeight + this.headerHeight)){
                    this.makeFullScreen()
                    this.debounceResize = null
                    return 
                }

                const widthGrowth  = window.innerWidth * this.widthGrowthBy.width   +  window.innerHeight * this.widthGrowthBy.height
                const heightGrowth = window.innerWidth * this.heightGrowthBy.width  +  window.innerHeight * this.heightGrowthBy.height

                const wVal = Math.round( this.minWidth  + widthGrowth )
                const hVal = Math.round( this.minHeight + heightGrowth )

                if( this.isWidthOverflowing(wVal) || this.isHeightOverflowing(hVal) || this.isHeightOverflowing(hVal + this.headerHeight)){
                    this.makeFullScreen()
                    this.debounceResize = null
                    return 
                }

                this.updateDimensionStyle(
                    `${hVal}px`, 
                    `${wVal}px`, 
                    'auto'
                )

                if(window.innerWidth < 700){
                    this.makeFullScreen()
                    this.debounceResize = null
                    return 
                }

                this.debounceResize = null
            }, 100)
        },
        
        isWidthOverflowing(width){
            return window.innerWidth < width
        },

        isHeightOverflowing(height){
            return window.innerHeight < height
        },

        updateDimensionStyle(height, width, left){
            this.$refs.dropdown.style.height = height
            this.$refs.dropdown.style.width = width
            this.$refs.dropdown.style.left = left
        },

        makeFullScreen(){
            this.$refs.dropdown.style.height = `${window.innerHeight - 3*16}px`
            this.$refs.dropdown.style.width = '100%'
            this.$refs.dropdown.style.left = 0
        },

        chatNav(componentName){
            for(let i in this.showNav){
                this.showNav[i] = componentName == i
            }
        },

        clickOutside(e){
            if(!this.isDeepChild(this.$refs.wrap, e.target)){
                this.removeClickListener()
            }
        },

        isDeepChild(p, c){while((c=c.parentNode)&&c!==p);return !!c},

        addClickListener(){
            document.addEventListener('click', this.clickOutside)
            this.showDrop = true
        },

        removeClickListener(){
            document.removeEventListener('click', this.clickOutside)
            this.showDrop = false
        },

        toggleClickListener(){
            this.showDrop
                ? this.removeClickListener()
                : this.addClickListener()
        },

    },
}
</script>