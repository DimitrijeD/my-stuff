<template>
    <div class="text-center invisible">
        <TransitionGroup tag="div" name="fade" class="invisible space-y-2 relative">
            <ActionResponseCard 
                v-for="response in container" :key="response.id"
                :response="response"
                @acknowledged="acknowledged"
                :colors="colors(response.response_type)"
                :dieAfter="dieAfter"
                :class="[cardCls]"
            />
        </TransitionGroup>
    </div>
</template>

<script>
import * as ns from '@/Store/module_namespaces.js'

import ActionResponseCard from '@/Components/ActionResponse/ActionResponseCard.vue'

export default {
    props: [ 'moduleId', 'dieAfter', 'cardCls' ],

    components: {
        ActionResponseCard
    },

    data(){
        return {
            container: {},
            collectedIds: [],
        }
    },

    computed: {
        id(){ 
            return this.$store.getters[ns.actionResponse(this.moduleId, 'id')] 
        },
    },

    created(){
        this.$store.dispatch(ns.actionResponseManager('initModule'), this.moduleId).then(() => {
            this.$watch('id', (newId) => {
                // Message is stored in component
                if(this.container[newId]) return

                // make new message
                this.container[newId] = {
                    messages: this.$store.getters[ns.actionResponse(this.moduleId, 'messages')],
                    response_type: this.$store.getters[ns.actionResponse(this.moduleId, 'response_type')],
                    id: newId, 
                }

                this.collectedIds.push(newId)

                this.$store.dispatch(ns.actionResponse(this.moduleId, 'flush'), newId)
            })
        })
    },

    methods: {
        acknowledged(id){
            delete this.container[id]
        },

        colors(response_type){
            if(response_type == 'success') {
                return {
                    wrap: 'bg-white border-green-400/50 bg-gradient-to-r dark:from-darker-200 dark:to-darker-300 dark:border-green-600/50',
                    wrapShadow: 'shadow-lg shadow-green-600/20 dark:shadow-green-400/20',
                    iconMouseIn: 'fill-green-500 stroke-green-600 hover:fill-green-600 hover:stroke-green-700 dark:hover:fill-green-300 dark:hover:stroke-green-500',
                    iconMouseOut: 'fill-green-500 stroke-green-500 dark:fill-green-600 dark:stroke-green-600',
                    txtMouseIn: 'text-green-600',
                    txtMouseOut: 'text-green-700 ',
                    slideMouseIn : 'bg-green-600 dark:bg-green-600 opacity-60',
                    slideMouseOut : 'bg-green-700 dark:bg-green-700 opacity-50 ',
                }
            }

            if(response_type == 'error') {
                return {
                    wrap: 'bg-white border-red-400/50 dark:bg-darker-200 dark:border-red-900/50',
                    wrapShadow: 'shadow-lg shadow-red-600/20 dark:shadow-red-400/20',
                    iconMouseIn: 'fill-transparent stroke-red-600  hover:stroke-red-700 dark:hover:stroke-red-500',
                    iconMouseOut: 'fill-transparent stroke-red-500 dark:stroke-red-600',
                    txtMouseIn: 'text-red-600 ',
                    txtMouseOut: 'text-red-700 ',
                    slideMouseIn : 'bg-red-600 dark:bg-red-600 opacity-60',
                    slideMouseOut : 'bg-red-700 dark:bg-red-700 opacity-50',
                }
            }

            if(response_type == 'info') {
                return {
                    wrap: 'bg-gray-200 border-gray-400/50 dark:bg-darker-200 dark:border-darker-900/50',
                    wrapShadow: 'shadow-lg shadow-gray-600/20 dark:shadow-darker-400/20',
                    iconMouseIn: 'fill-transaprent stroke-blue-500 hover:stroke-blue-600 dark:hover:stroke-blue-600',
                    iconMouseOut: 'fill-transaprent stroke-blue-500 dark:stroke-blue-500',
                    txtMouseIn: 'text-blue-500',
                    txtMouseOut: ' text-blue-600 ',
                    slideMouseIn : 'bg-blue-500 dark:bg-blue-500 opacity-60',
                    slideMouseOut : 'bg-blue-500 dark:bg-blue-500 opacity-50 ',
                }
            }

            return {
                wrap: 'bg-gray-200 border-gray-400/50 dark:bg-darker-200 dark:border-darker-900/50',
                wrapShadow: 'shadow-lg shadow-gray-400/20 dark:shadow-darker-600/20',
                iconMouseIn: 'fill-transaprent stroke-gray-500 hover:stroke-gray-600 dark:hover:stroke-gray-600',
                iconMouseOut: 'fill-transaprent stroke-gray-500 dark:stroke-gray-500',
                txtMouseIn: 'text-gray-600 dark:text-gray-300',
                txtMouseOut: 'opacity-90',
                slideMouseIn : 'bg-gray-500 dark:bg-gray-500 opacity-60',
                slideMouseOut : 'bg-blue-500 dark:bg-blue-500 opacity-50',
            }
        },

    }

}

</script>

<style scoped>

.fade-move,
.fade-enter-active,
.fade-leave-active{
    transition: all 0.5s cubic-bezier(0.55, 0, 0.1, 1);
}

.fade-enter-from,
.fade-leave-to{
    opacity: 0;
    transform: scaleY(0.01) translate(40px, 0);
}

.fade-leave-active {
    position: absolute;
    width: 100%;
    
}
</style>