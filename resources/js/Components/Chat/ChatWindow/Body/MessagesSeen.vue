<template>
    <div class="flex justify-end flex-wrap">
        <div v-for="participant_id in user_ids" :key="participant_id">
            <img
                v-if="toShow(participant_id)"
                :src="getParticipantThumbnail(participant_id)"
                :class="['w-7 h-7 object-cover m-0.5 rounded-full']"
                alt="User thumbnail"
            >    
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import * as ns from '@/Store/module_namespaces.js'

export default {
    props:[
        'group_id', 'message', "user_ids"
    ],

    data(){
        return{
            gm_ns: ns.groupModule(this.group_id),

            classes: {
                self: 'shadow-small-img-self',
                notSelf: 'shadow-small-img-other'
            },

            config: {
                neverShowUserBewlowHisOwnMsg: true,
                neverShowSelf: true,
            }
        }
    },

    computed: {
        ...mapGetters({ 
            user: "user",
        }),

    },

    created(){
        
    },

    methods: 
    {
        hasAnybodySeenThis(){ return this.user_ids.length > 0 ? true : false },

        getParticipantThumbnail(id) { return this.$store.getters[this.gm_ns + '/getParticipantThumbnail'](id) },

        toShow(participant_id){
            if(this.config.neverShowUserBewlowHisOwnMsg){
                if(this.isMsgOwner(participant_id)) return false
            }

            if(this.config.neverShowSelf){
                if(this.isSelf(participant_id)) return false
            }

            return true
        },

        isMsgOwner(participant_id){
            return this.message.user_id == participant_id
        },

        isSelf(participant_id){
            return this.user.id == participant_id
        }
    }

}
</script>
