<template>
    <div class="">
        <input 
            class="auth-input" 
            :type="type ? type : 'text'" 
            :name="name" 
            v-model="value" 
            @input="onInput" 
            :placeholder=placeholder
            :required="required ? true : false" 
        />
        <p class="text-red-500" v-for="(error, index) in errors" :key="index">
            {{ error }}
        </p>
    </div>
</template>

<script>
export default{
    props: [
        'name', 'placeholder', "errors", "type", "required"
    ],

    data(){
        return {
            value: ""
        }
    },

    methods: {

        onInput(event) {
            this.$emit('inputC', {
                key: this.name,
                value: this.value,
            });
        }
    }
}
</script>