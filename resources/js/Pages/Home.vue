<template>
    <div class="flex flex-col ">
        <div class="no"></div>
        <div class="sm-cls"></div>
        <div class="md-cls"></div>
        <div class="lg-cls"></div>
        <div class="xl-cls"></div>
    </div>

    
    <!-- <div class="w-96 h-96 bg-orange-900 fixed" @mouseleave="pause" @mouseenter="play">
        <div ref="box" class="box"></div>
    </div> -->

<!-- <div class="flex flex-col min-h-screen max-h-screen overflow-hidden bg-blue-900 ">
    <nav>
        <ul class="flex">
            <li class="p-2">asd</li>
            <li class="p-2">asd</li>
            <li class="p-2">asd</li>
            <li class="p-2">asd</li>
        </ul>
    </nav>
    <main class="flex grow overflow-hidden">
        <div class="flex flex-col overflow-hidden text-black">
            <p>Right column</p>
            <ul class="flex-1 overflow-y-auto bg-green-900">
                <li class="py-20">Item 1</li>
                <li class="py-20">Item 2</li>
                <li class="py-20">Item 3</li>
                <li class="py-20">Item 4</li>
                <li class="py-20">Item 5</li>
                <li class="py-20">Item 6</li>
            </ul>
        </div>
    </main>
</div>  -->

</template>

<script>


export default{
    data(){
        return {

        }
    },

    computed:{

    },

    created(){
     
    },

    mounted(){
        window.onresize = () => {
            // console.log(window.innerWidth)
            // console.log(window.innerHeight)
        }
    },

    methods: {

    }
}
</script>

<style scoped>
.no      { @apply m-2 py-20    bg-blue-900;}
.sm-cls  { @apply m-2 py-20 sm:bg-sky-900;}
.md-cls  { @apply m-2 py-20 md:bg-red-900;}
.lg-cls  { @apply m-2 py-20 lg:bg-green-900;}
.xl-cls  { @apply m-2 py-20 xl:bg-orange-900;}



</style>