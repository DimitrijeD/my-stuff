<template>
    <ExamplesLayout>
        <template #layout-name>
            <h1>GroupCardLayout examples</h1>
        </template>

        <template #info-ul>
            <li>This layout is small card that displays general information about chat group</li>
            <li>It resides in list of all chat groups</li>
        </template>

        <template #issues-ul>
            <li></li>
        </template>

        <template #body>

            <GroupCardLayout v-if="group" :seen="seen" :atLeastTwoParticpants="atLeastTwoParticpants" :groupName="group.name">
                <template #group-name>
                    {{group.name ? group.name : null}}
                </template>

                <template #participants>
                    <div v-for="p in group.participants">
                        <SmallUser 
                            v-if="p.id !== user.id"
                            :user="p" 
                            :imgCls="'w-9 h-9'" 
                            :layoutCls="'py-1 pl-1'"
                        />
                    </div>
                </template>

                <template #last-message>
                    <MessageCard 
                        v-if="lastMessage"
                        :message="lastMessage"
                    />
                </template>
                
            </GroupCardLayout>


        </template>
    </ExamplesLayout>
</template>

<script>
import { mapGetters } from "vuex"
import SmallUser from   '@/Components/Reuseables/SmallUser.vue';
import MessageCard from "@/Components/Chat/reuseables/MessageCard.vue"
import ExamplesLayout from '@/Layouts/ExamplesLayout.vue'
import GroupCardLayout from '@/Layouts/GroupCardLayout.vue'

export default {
    components:{
        GroupCardLayout,
        ExamplesLayout,
        SmallUser,
        MessageCard,
    },

    computed:{
        ...mapGetters({ 
            user: "user",
        }),

        group(){ return this.$store.getters[this.gm_ns + '/state'] },

        lastMessage(){ return this.$store.getters[this.gm_ns + '/last_message'] },

        atLeastTwoParticpants(){ return Object.keys(this.group.participants).length >= 2 },

        seen(){ return this.$store.getters[`${this.gm_ns}/seen`] },
    },


    data(){
        return {
            txtOneParticipant: "Only you.",
            gm_ns: ns.groupModule(36),
        }
    },

    created(){

    },

    methods: {
        hasLastMessage(msg){ return msg.hasOwnProperty('id') ? true : false },
    },
}
</script>

<style scoped>
.test1 {
    @apply h-14 fill-white ; 
}
</style>