<template>
    <div class="select-none relative rounded sm:cursor-pointer" @mouseenter="mouseEnter()" @mouseleave="mouseLeave()" @click="click()">
        <div class="relative flex flex-justify-between" :class="[headCls]">
            <span :class="[currentCls]">
                {{ current }}
            </span>
            <ArrowHeadIcon class="absolute right-2" :class="[arrowCls ? arrowCls : 'w-6 h-6 fill-blue-500 stroke-blue-500']" />
        </div>
        <div v-show="show && !lockedOnSelect" class="overflow-hidden absolute w-full rounded-b-lg" :class="[itemsWrapCls ? itemsWrapCls : 'py-2']">
            <p v-for="(item, index) in items" @click="selected({ raw: index, forHumans: item })" :class="[itemCls]">
                {{ item }}
            </p>
        </div>
    </div>
</template>

<script>
import ArrowHeadIcon from '@/Components/Reuseables/Icons/ArrowHeadIcon.vue'

/**
 * @todo lockedOnSelect doesnt work because component is recreated on update...
 */
export default {
    props: [ 'current', 'items', 'headCls', 'currentCls', 'arrowCls', 'itemsWrapCls', 'itemCls'  ],

    components: { ArrowHeadIcon, },

    data(){
        return {
            show: false,
            isMobile: 640 > window.innerWidth,
            lockedOnSelect: false,
            lockFor: 2000
        }
    },

    created(){
        
    },

    methods: {
        selected(item){
            this.$emit('selected', item)
            this.lockedOnSelect = true

            setTimeout(() => {
                this.lockedOnSelect = false
            }, this.lockFor);
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
