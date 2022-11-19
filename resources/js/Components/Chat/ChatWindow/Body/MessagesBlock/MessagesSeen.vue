<template>
    <TransitionGroup tag="div" name="list" :class="['relative flex justify-end flex-wrap gap-0.5', anybodyDisplayed ? 'mt-1.5' : '']">
        <div v-for="participant_id in user_ids" :key="participant_id">
            <img
                v-if="toShow(participant_id)"
                :src="getParticipantThumbnail(participant_id)"
                class="w-7 h-7 object-cover m-0.5 rounded-full"
                alt="User thumbnail"
            >    
        </div>
    </TransitionGroup>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    inject: ['group_id'],

    props:[ 'message', "user_ids" ],

    data(){
        return {
            config: {
                neverShowUserBewlowHisOwnMsg: true,
                neverShowSelf: true,
            },
            anybodyDisplayed: false,
        }
    },

    computed: {
        ...mapGetters({ 
            user_id: "user_id",
        }),
    },

    methods: {
        hasAnybodySeenThis(){ return this.user_ids.length > 0 ? true : false },

        getParticipantThumbnail(id) { return this.$store.getters[ns.groupModule(this.group_id, 'getParticipantThumbnail')](id) },

        toShow(participant_id){
            if(this.config.neverShowUserBewlowHisOwnMsg){
                if(this.isMsgOwner(participant_id)) return false
            }

            if(this.config.neverShowSelf){
                if(this.user_id == participant_id) return false
            }

            this.anybodyDisplayed = true
            return true
        },

        isMsgOwner(participant_id){
            return this.message.user_id == participant_id
        },

    }

}
</script>

<style scoped>
.list-move,
.list-enter-active,
.list-leave-active{
    transition: all 0.3s ease-out;
}

.list-enter-from,
.list-leave-to{
    opacity: 0;
    transform: translateX(20px);
}

.list-leave-active {
    position: absolute;
}
</style>