<template>
    <img 
        :class="imgCls" 
        :src="blob" 
        alt="image"
    >
</template>

<script>

export default {
    inject: ['group_id'],

    props: ['url', 'imgCls' ],

    data(){
        return {
            
        }
    },

    computed: {
        blob(){
            return this.$store.getters[ ns.groupModule(this.group_id, 'filesM/blob') ](this.url)
        }
    },

    mounted(){
        this.getImage()
    },

    methods: {
        getImage(){
            this.$store.dispatch( ns.groupModule(this.group_id, 'filesM/getImage'), this.url)
        },
    }
}
</script>