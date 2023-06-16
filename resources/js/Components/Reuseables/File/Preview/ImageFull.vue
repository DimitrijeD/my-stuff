<template>
    <div :class="[
        'border-2 overflow-hidden flex items-center rounded', 
        selected ? 'border-darker-700/70' : 'border-darker-700/20'
    ]">
        <Image 
            :url="url"
            :imgCls="''"
        />
    </div>
</template>

<script>
import Image from '@/Components/Reuseables/Image.vue';

export default {
    props: ['url', 'selected'],

    components: { Image, },

}
</script>