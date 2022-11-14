<template>
    <div class="">
        <div class="mb-4">
            <button v-if="!interval" @click="start" class="p-4 bg-darker-300">start</button>
            <button v-if="interval"  @click="end"   class="p-4 bg-darker-300">end</button>
        </div>

        <div v-for="index in inc" class="flex flex-row">
            <!-- <div :class="[' dim ']" :style="[getStyle(index)]"></div> -->
            <div v-for="index1 in inc" class="flex">
                <div :class="[' dim ']" :style="[getStyle(index1)]"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            inc: 1,
            interval: null
        }
    },

    methods: {
        getStyle(index){
            let val = 0
            for (let i = 20; i > 0; i--){
                if(index%i == 0 ){
                    val = (index * i)%(window.innerWidth-60)
                    return {
                        'transform': `translateX(${val%100}px) translateY(${((-1) * val)%100}px) rotate(${(val * i)%20}deg)`,
                        'background-color': `hsl(${(index * i)%360}, 65%, 40%)`,
                    }
                }    
            }
        },

        start(){
            this.interval = setInterval(()=>{
                this.inc++
            }, 100)
        },

        end(){
            clearInterval(this.interval)
            this.interval= null
        }
    },

}
</script>

<style scoped>


.dim {
    @apply w-4 h-1 opacity-70;
}
</style>