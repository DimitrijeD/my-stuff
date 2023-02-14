<template>
    <TransitionGroup tag="div" name="list" class="py-1 space-y-0.5 relative overflow-hidden" v-show="someoneTyping.length">
        <p v-for="(someone, index) in someoneTyping" :key="index" class="italic text-sm text-gray-500 dark:text-gray-400 ml-2 select-none">
            {{ someone }} 
        </p>
    </TransitionGroup>
</template>

<script>
export default {
    inject: ['group_id'],

    data(){
        return{
            showNtypers: 5,
        }
    },

    computed: {
        someoneTyping(){
            let typers = this.$store.getters[ ns.groupModule(this.group_id, 'participantsM/usersTyping') ]
            let num = typers.length

            if(num == 0) return []

            if(num == 1){
                return [
                    `${this.getUserName(typers[0])} is typing...`
                ]
            }

            if(num < this.showNtypers) return this.prepareMultipleTyping(typers, num)
            
            typers = typers.slice(0, this.showNtypers)

            return this.prepareMultipleTyping(typers, typers.length, num - this.showNtypers)
        },

        participants(){ 
            return this.$store.getters[ ns.groupModule(this.group_id, 'participantsM/participants') ]
        },
    },

    mounted() {

    },

    methods:{
        oneIsTyping(){
            return 'is typing ...'
        },

        getUserName(id){
            if(this.participants[id]){
                return this.participants[id].first_name
            }

            for(let i in this.participants){
                if(this.participants[i].id == id){
                    return this.participants[i].first_name
                }
            }
        },

        prepareMultipleTyping(typers, num, remaining = null){
            let display = []

            for(let i = 0; i < num; i++){
                display.push(`${this.getUserName(typers[i])},`)
            }

            // remove ',' char from last user in list
            display[display.length - 1] = display[display.length - 1].slice(0, -1)

            remaining
                ? display[display.length - 1] += ` and ${remaining} are typing.`
                : display[display.length - 1] += ` are typing.`

            return display
        },
    },

}
</script>

<style scoped>
    .list-move,
    .list-enter-active,
    .list-leave-active{
        transition: all 0.1s ease-in;
    }
    
    .list-enter-from,
    .list-leave-to{
        opacity: 0;
        transform: scaleY(0.01) translateX(-10px);
    }
    
    .list-leave-active {
        position: absolute;
    }

</style>