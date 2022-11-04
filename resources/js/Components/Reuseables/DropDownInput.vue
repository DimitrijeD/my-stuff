<template>
    <div class="relative rounded sm:cursor-pointer" @mouseenter="mouseEnter()" @mouseleave="mouseLeave()" @click="click()">
        <div class="relative flex flex-justify-between" :class="[headCls]">
            <span :class="[currentCls]">
                {{ current }}
            </span>
            <ArrowHeadIcon class="absolute right-2" :class="[arrowCls ? arrowCls : 'w-6 h-6 fill-blue-500 stroke-blue-500']" />
        </div>
        <div v-if="show" class="overflow-hidden absolute w-full rounded-b-lg" :class="[itemsWrapCls ? itemsWrapCls : 'py-2']">
            <p v-for="item in items" @click="selected(item)" :class="[itemCls]">
                {{ item }}
            </p>
        </div>
    </div>
</template>

<script>
import ArrowHeadIcon from '@/Components/Reuseables/Icons/ArrowHeadIcon.vue'

export default {
    props: [ 'current', 'items', 'headCls', 'currentCls', 'arrowCls', 'itemsWrapCls', 'itemCls'  ],

    components: { ArrowHeadIcon, },

    data(){
        return {
            show: false,
            isMobile: 640 > window.innerWidth
        }
    },

    methods: {
        selected(item){
            this.$emit('selected', item)
        },

        mouseEnter(){
            if(!this.isMobile){
                this.show = true
            }
        },

        mouseLeave(){
            if(!this.isMobile){
                this.show = false
            }
        },

        click(){
            this.show = !this.show
        },

    }
}
</script>
