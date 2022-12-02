import defaultState from './defaultState.js'
import * as h from '@/Store/functions/helpers.js'

const mutations = {
    group_id: (state, id) => state.group_id = id,

    addParticipants: (state, participants) => state.participants = { ...state.participants, ...h.createDict(participants, 'id') },
    
    updateParticipantRole: (state, data) => state.participants[data.participant_id].pivot.participant_role = data.participant_role,

    participantAcceptedToJoinGroup: (state, participant_id) => state.participants[participant_id].pivot.accepted = true,

    updatePivot(state, data) {
        state.participants[data.participant_id].pivot.last_message_seen_id = data.last_message_seen_id
        state.participants[data.participant_id].pivot.updated_at           = data.now
    },

    deleteParticipant (state, participant_id) { delete state.participants[participant_id] },

    removeTyper(state, id){
        if(state.typing.user_ids.includes(id))
            state.typing.user_ids.splice(state.typing.user_ids.indexOf(id), 1)
    },

    addTyper(state, id){
        if(!state.typing.user_ids.includes(id))
            state.typing.user_ids.unshift(id)
    },

    setTypingTimeout(state, id){
        clearTimeout(state.typing.timeouts[id])

        state.typing.timeouts[id] = setTimeout(() => {
            // removeTyper Mutation 
            if(state.typing.user_ids.includes(id))
                state.typing.user_ids.splice(state.typing.user_ids.indexOf(id), 1)
        }, state.typing.showTyperFor)
    },
}

export default mutations 