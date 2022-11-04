<template>
    <div>
        <label class="inline-flex relative items-center cursor-pointer">
        <input 
            type="checkbox" 
            class="sr-only peer"
            v-model="value"
            @input="onInput"
        >
        <div class="w-11 h-6 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:border after:rounded-full after:h-5 after:w-5 after:transition-all
        bg-gray-200 after:bg-white after:border-gray-300 peer-checked:bg-blue-600 peer-checked:after:border-white
        dark:bg-gray-700 dark:border-gray-600 
        "></div>
        </label>
    </div>
</template>

<script>
export default{
    props: [
        'name', 'value'
    ],

    data(){
        return {
            
        }
    },

    methods: {

        onInput(event) {
            
            this.$emit('inputC', {
                key: this.name,
                value: this.value ? true : false,
            });
        }
    }
}
</script>